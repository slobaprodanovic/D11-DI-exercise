<?php

namespace Drupal\dependency_injection_exercise\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\dependency_injection_exercise\Service\PhotosService;

/**
 * Provides the rest output.
 */
class RestOutputController extends ControllerBase {

  /**
   * The default album ID.
   *
   * @var int
   */
  CONST DEFAULT_ALBUM_ID = 5;

  /**
   * The photos service.
   *
   * @var \Drupal\dependency_injection_exercise\Service\PhotosService
   */
  protected PhotosService $photosService;

  /**
   * Constructs a RestOutputController object.
   *
   * @param \Drupal\dependency_injection_exercise\Service\PhotosService $photosService
   *   The photos service.
   */
  public function __construct(PhotosService $photosService) {
    $this->photosService = $photosService;
  }

  /**
   * {@inheritdoc}
   */
  public static function create($container): RestOutputController {
    return new self(
      $container->get('dependency_injection_exercise.photos_service')
    );
  }

  /**
   * Displays the photos.
   *
   * @return array[]
   *   A renderable array representing the photos.
   */
  public function showPhotos(): array {
    return $this->photosService->getPhotos(self::DEFAULT_ALBUM_ID);
  }

}
