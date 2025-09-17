<?php

namespace Drupal\dependency_injection_exercise;

use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\Core\Routing\CurrentRouteMatch;
use Drupal\Core\Url;
use Psr\Log\LoggerInterface;

/**
 * Simple decorator for the LanguageManager service.
 */
class LanguageManagerDecorator implements LanguageManagerInterface {

  /**
   * The decorated language manager service.
   *
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  protected LanguageManagerInterface $inner;

  /**
   * The logger service.
   *
   * @var \Psr\Log\LoggerInterface
   */
  protected LoggerInterface $logger;

  /**
   * The current route match service.
   *
   * @var \Drupal\Core\Routing\CurrentRouteMatch
   */
  protected CurrentRouteMatch $currentRoute;

  /**
   * A flag to ensure logging happens only once per request.
   *
   * @var bool
   */
  protected bool $logged = FALSE;

  /**
   * Constructs a LanguageManagerDecorator object.
   *
   * @param \Drupal\Core\Language\LanguageManagerInterface $inner
   *   The decorated language manager service.
   */
  public function __construct(LanguageManagerInterface $inner, LoggerInterface $logger, CurrentRouteMatch $currentRoute) {
    $this->inner = $inner;
    $this->logger = $logger;
    $this->currentRoute = $currentRoute;
  }

  /**
   * @inheritDoc
   */
  public function isMultilingual(): bool {
    return $this->inner->isMultilingual();
  }

  /**
   * @inheritDoc
   */
  public function getLanguageTypes(): array {
    return $this->inner->getLanguageTypes();
  }

  /**
   * @inheritDoc
   */
  public function getDefinedLanguageTypesInfo(): array {
    return $this->inner->getDefinedLanguageTypesInfo();
  }

  /**
   * @inheritDoc
   */
  public function getCurrentLanguage($type = LanguageInterface::TYPE_INTERFACE): LanguageInterface {
    $route = $this->currentRoute->getRouteName();
    if ('dependency_injection_exercise.rest_output_controller_photos' === $this->currentRoute->getRouteName() && !$this->logged) {
      $language = $this->inner->getCurrentLanguage($type);
      $this->logger->info('Language manager is over taken. Current language: {lang} for route {route}', [
        'lang' => $language->getId(),
        'route' => $route,
      ]);

      $this->logged = TRUE;
    }

    return $this->inner->getCurrentLanguage($type);
  }

  /**
   * @inheritDoc
   */
  public function reset($type = NULL): LanguageManagerDecorator|LanguageManagerInterface {
    return $this->inner->reset($type);
  }

  /**
   * @inheritDoc
   */
  public function getDefaultLanguage(): LanguageInterface {
    return $this->inner->getDefaultLanguage();
  }

  /**
   * @inheritDoc
   */
  public function getLanguages($flags = LanguageInterface::STATE_CONFIGURABLE): array {
    return $this->inner->getLanguages($flags);
  }

  /**
   * @inheritDoc
   */
  public function getNativeLanguages(): array {
    return $this->inner->getNativeLanguages();
  }

  /**
   * @inheritDoc
   */
  public function getLanguage($langcode): ?LanguageInterface {
    return $this->inner->getLanguage($langcode);
  }

  /**
   * @inheritDoc
   */
  public function getLanguageName($langcode): string {
    return $this->inner->getLanguageName($langcode);
  }

  /**
   * @inheritDoc
   */
  public function getDefaultLockedLanguages($weight = 0): array {
    return $this->inner->getDefaultLockedLanguages($weight);
  }

  /**
   * @inheritDoc
   */
  public function isLanguageLocked($langcode): bool {
    return $this->inner->isLanguageLocked($langcode);
  }

  /**
   * @inheritDoc
   */
  public function getFallbackCandidates(array $context = []): array {
    return $this->inner->getFallbackCandidates($context);
  }

  /**
   * @inheritDoc
   */
  public function getLanguageSwitchLinks($type, Url $url): ?object {
    return $this->inner->getLanguageSwitchLinks($type, $url);
  }

  /**
   * @inheritDoc
   */
  public function setConfigOverrideLanguage(?LanguageInterface $language = NULL): LanguageManagerDecorator|LanguageManagerInterface {
    return $this->inner->setConfigOverrideLanguage($language);
  }

  /**
   * @inheritDoc
   */
  public function getConfigOverrideLanguage(): LanguageInterface {
    return $this->inner->getConfigOverrideLanguage();
  }

  /**
   * @inheritDoc
   */
  public static function getStandardLanguageList(): array {
    return LanguageManagerInterface::getStandardLanguageList();
  }

}
