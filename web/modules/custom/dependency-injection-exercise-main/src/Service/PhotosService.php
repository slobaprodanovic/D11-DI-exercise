<?php

namespace Drupal\dependency_injection_exercise\Service;

use Drupal\Component\Serialization\Json;
use Drupal\Core\StringTranslation\TranslationInterface;
use GuzzleHttp\ClientInterface;
use Psr\Log\LoggerInterface;

/**
 * Service to interact with the Photos API.
 */
class PhotosService {

  /**
   * The HTTP client to make requests.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected ClientInterface $httpClient;

  /**
   * The logger service.
   *
   * @var \Psr\Log\LoggerInterface
   */
  protected LoggerInterface $logger;

  /**
   * The string translation service.
   *
   * @var \Drupal\Core\StringTranslation\TranslationInterface
   */
  protected TranslationInterface $stringTranslation;

  /**
   * Constructs a PhotosService object.
   *
   * @param  \GuzzleHttp\ClientInterface  $httpClient
   *   The HTTP client to make requests.
   * @param  \Psr\Log\LoggerInterface  $logger
   *   The logger service.
   * @param  \Drupal\Core\StringTranslation\TranslationInterface  $translation
   *   The string translation service.
   */
  public function __construct(ClientInterface $httpClient, LoggerInterface $logger, TranslationInterface $translation) {
    $this->httpClient = $httpClient;
    $this->logger = $logger;
    $this->stringTranslation = $translation;
  }

  /**
   * Fetches and returns photos from a specified album.
   *
   * @param int|null $albumId
   *   The ID of the album to fetch photos from. If NULL, a random album ID
   *   between 1 and 20 will be used.
   *
   * @return array
   *   A renderable array representing the photos or an error message.
   */
  public function getPhotos(?int $albumId = NULL): array {
    $build = [
      '#cache' => [
        'max-age' => 60,
        'contexts' => ['url'],
      ],
    ];

    try {
      $albumId = $albumId ?? random_int(1, 20);

      $response = $this->httpClient->request(
        'GET',
        sprintf('https://jsonplaceholder.typicode.com/albums/%s/photos', $albumId)
      );

      $data = Json::decode($response->getBody()->getContents()) ?? [];
      if (empty($data)) {
        $this->logger->warning('No photos found for album {id}', ['id' => $albumId]);
        return $this->buildError($build);
      }

      $build['photos'] = array_map(static fn(array $item) => [
        '#theme' => 'image',
        '#uri' => $item['thumbnailUrl'],
        '#alt' => $item['title'],
        '#title' => $item['title'],
      ], $data);
    }
    catch (\Throwable $e) {
      $this->logger->error('Failed to fetch photos for album {id}: {message}', [
        'id' => $albumId,
        'message' => $e->getMessage(),
      ]);
      return $this->buildError($build);
    }

    return $build;
  }

  /**
   * Builds an error message render array.
   *
   * @param array $build
   *   The existing build array to append the error message to.
   *
   * @return array
   *   The modified build array with the error message.
   */
  private function buildError(array $build): array {
    $build['error'] = [
      '#type' => 'html_tag',
      '#tag' => 'p',
      '#value' => $this->stringTranslation->translate('No photos available.'),
    ];

    return $build;
  }
}
