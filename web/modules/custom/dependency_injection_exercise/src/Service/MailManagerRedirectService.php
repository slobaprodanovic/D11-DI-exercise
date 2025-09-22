<?php

namespace Drupal\dependency_injection_exercise\Service;

use Drupal\Core\Mail\MailManager;

/**
 * Class MailManagerRedirect.
 */
class MailManagerRedirectService extends MailManager {

  /**
   * The email address to redirect to.
   */
  protected string $redirectTo = 'nope@doesntexist.com';

  /**
   * Sets the email address to redirect to.
   */
  public function setRedirectTo(string $redirectTo): void {
    $this->redirectTo = $redirectTo;
  }

  /**
   * Returns the email address to redirect to.
   */
  public function getRedirectTo(): string {
    return $this->redirectTo;
  }

  /**
   * {@inheritdoc}
   */
  public function mail($module, $key, $to, $langcode, $params = [], $reply = NULL, $send = TRUE) {
    $to = $this->getRedirectTo();

    parent::mail($module, $key, $to, $langcode, $params, $reply, $send);
  }

}
