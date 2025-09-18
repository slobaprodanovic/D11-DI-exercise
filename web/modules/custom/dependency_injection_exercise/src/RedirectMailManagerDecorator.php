<?php

namespace Drupal\dependency_injection_exercise;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Mail\MailManager;
use Drupal\Core\Mail\MailManagerInterface;
use Drupal\Core\Render\RendererInterface;
use Drupal\Core\StringTranslation\TranslationInterface;

/**
 * Class RedirectMailManagerDecorator.
 *
 * A simple mail manager decorator that currently does nothing but can be extended
 * to modify email behavior (e.g., redirecting emails, logging, etc.).
 */
class RedirectMailManagerDecorator extends MailManager {

  /**
   * The email address to which all emails will be redirected.
   *
   * @var string
   */
  protected string $redirectTo = 'nope@doesntexist.com';

  /**
   * The decorated mail manager service.
   *
   * @var \Drupal\Core\Mail\MailManagerInterface
   */
  protected MailManagerInterface $inner;

  /**
   * Constructs the decorator.
   */
  public function __construct(
    MailManagerInterface $inner,
    \Traversable $namespaces,
    CacheBackendInterface $cache,
    ModuleHandlerInterface $module_handler,
    TranslationInterface $translation,
    LoggerChannelFactoryInterface $logger_factory,
    ConfigFactoryInterface $config_factory,
    RendererInterface $renderer
  ) {

    $this->inner = $inner;

    parent::__construct($namespaces, $cache, $module_handler, $config_factory, $logger_factory, $translation,  $renderer);
  }

  /**
   * Sets the email address to which all emails will be redirected.
   * Needed for Unit Tests.
   *
   * @param string $email
   *   The email address.
   */
  public function setRedirectTo(string $email): void {
    $this->redirectTo = $email;
  }

  /**
   * {@inheritdoc}
   */
  public function mail($module, $key, $to, $langcode, $params = [], $reply = NULL, $send = TRUE) {
    $to = $this->redirectTo;
    return $this->inner->mail($module, $key, $to, $langcode, $params, $reply, $send);
  }

}
