<?php

namespace Drupal\Tests\dependency_injection_exercise\Unit;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Render\RendererInterface;
use Drupal\Core\StringTranslation\TranslationInterface;
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

    $mockInner->expects($this->once())
      ->method('mail')
      ->with(
        $this->anything(),
        $this->anything(),
        'test@example.com',
        $this->anything(),
        $this->anything(),
        $this->anything(),
        $this->anything()
      )
      ->willReturn(1);

    $decorator = new RedirectMailManagerDecorator(
      $mockInner,
      new ArrayIterator([]),
      $this->createMock(CacheBackendInterface::class),
      $this->createMock(ModuleHandlerInterface::class),
      $this->createMock(TranslationInterface::class),
      $this->createMock(LoggerChannelFactoryInterface::class),
      $this->createMock(ConfigFactoryInterface::class),
      $this->createMock(RendererInterface::class),
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

}
