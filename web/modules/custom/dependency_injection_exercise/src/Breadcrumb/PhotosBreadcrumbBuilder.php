<?php

namespace Drupal\dependency_injection_exercise\Breadcrumb;

use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface;
use Drupal\Core\Link;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Builds breadcrumb for the Photos page.
 */
class PhotosBreadcrumbBuilder implements BreadcrumbBuilderInterface {
  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public function applies(RouteMatchInterface $route_match): bool {
    return 'dependency_injection_exercise.rest_output_controller_photos' === $route_match->getRouteName();
  }

  /**
   * {@inheritdoc}
   */
  public function build(RouteMatchInterface $route_match): Breadcrumb {
    $breadcrumb = new Breadcrumb();
    $breadcrumb->addCacheContexts(['url.path', 'route']);

    $links = [];
    $links[] = Link::createFromRoute($this->t('Home'), '<front>');
    $links[] = Link::createFromRoute($this->t('Dropsolid'), '<none>');
    $links[] = Link::createFromRoute($this->t('Example'), '<none>');
    $links[] = Link::createFromRoute($this->t('Photos'), 'dependency_injection_exercise.rest_output_controller_photos');

    return $breadcrumb->setLinks($links);
  }

}
