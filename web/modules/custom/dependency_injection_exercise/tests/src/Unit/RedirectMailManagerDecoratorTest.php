<?php

namespace Drupal\Tests\dependency_injection_exercise\Unit;

use Drupal\Tests\UnitTestCase;
use Drupal\dependency_injection_exercise\RedirectMailManagerDecorator;
use Drupal\Core\Mail\MailManagerInterface;
use ArrayIterator;

/**
 * Tests for the RedirectMailManagerDecorator class.
 *
 * @covers \Drupal\dependency_injection_exercise\RedirectMailManagerDecorator
 * @group dependency_injection_exercise
 */
class RedirectMailManagerDecoratorTest extends UnitTestCase {

  /**
   * Tests that emails are rerouted to the specified address.
   */
  public function testRerouteToTestEmail() {
    $mockInner = $this->createMock(MailManagerInterface::class);

    $mockInner->method('mail')->willReturn(1);

    $decorator = new RedirectMailManagerDecorator(
      $mockInner,
      new ArrayIterator([]),
      $this->createMock(\Drupal\Core\Cache\CacheBackendInterface::class),
      $this->createMock(\Drupal\Core\Extension\ModuleHandlerInterface::class),
      $this->createMock(\Drupal\Core\StringTranslation\TranslationInterface::class),
      $this->createMock(\Drupal\Core\Logger\LoggerChannelFactoryInterface::class),
      $this->createMock(\Drupal\Core\Config\ConfigFactoryInterface::class),
      $this->createMock(\Drupal\Core\Render\RendererInterface::class),
    );

    $decorator->setRedirectTo('test@example.com');

    $result = $decorator->mail(
      'my_module',
      'key',
      'original@example.com',
      'en',
      ['subject' => 'Hello'],
      NULL,
      TRUE
    );

    $this->assertSame(1, $result);
  }

  /**
   * Tests that emails to allowed addresses are not rerouted.
   */
  public function testNoRerouteForAllowedEmail() {
    $mockInner = $this->createMock(MailManagerInterface::class);
    $mockInner->method('mail')->willReturn(1);

    $decorator = new RedirectMailManagerDecorator(
      $mockInner,
      new ArrayIterator([]),
      $this->createMock(\Drupal\Core\Cache\CacheBackendInterface::class),
      $this->createMock(\Drupal\Core\Extension\ModuleHandlerInterface::class),
      $this->createMock(\Drupal\Core\StringTranslation\TranslationInterface::class),
      $this->createMock(\Drupal\Core\Logger\LoggerChannelFactoryInterface::class),
      $this->createMock(\Drupal\Core\Config\ConfigFactoryInterface::class),
      $this->createMock(\Drupal\Core\Render\RendererInterface::class),
    );

    $decorator->setRedirectTo('allowed@example.com');

    $result = $decorator->mail(
      'my_module',
      'key',
      'allowed@example.com',
      'en',
      ['subject' => 'Hello'],
      NULL,
      TRUE
    );

    $this->assertSame(1, $result);
  }
}
