<?php

namespace Drupal\dependency_injection_exercise\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\dependency_injection_exercise\Service\PhotosService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'RestOutputBlock' block.
 *
 * @Block(
 *  id = "rest_output_block",
 *  admin_label = @Translation("Rest output block"),
 * )
 */
class RestOutputBlock extends BlockBase implements ContainerFactoryPluginInterface {
  /**
   * The photos service.
   *
   * @var \Drupal\dependency_injection_exercise\Service\PhotosService
   */
  protected PhotosService $photosService;

  public function __construct(array $configuration, $plugin_id, $plugin_definition, PhotosService $photosService) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->photosService = $photosService;
  }

  /**
   * @inheritDoc
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition): RestOutputBlock|ContainerFactoryPluginInterface|static {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('dependency_injection_exercise.photos_service')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    return $this->photosService->getPhotos();
  }

}
