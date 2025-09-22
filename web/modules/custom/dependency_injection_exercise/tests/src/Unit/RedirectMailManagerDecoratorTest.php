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
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;

/**
 * Tests for the RedirectMailManagerDecorator class.
 */
#[CoversClass(RedirectMailManagerDecorator::class)]
#[Group('dependency_injection_exercise')]
class RedirectMailManagerDecoratorTest extends UnitTestCase {

  /**
   * Tests that emails are rerouted to the specified address.
   */
  public function testRerouteToTestEmail() {
    $mockInner = $this->createMock(MailManagerInterface::class);

    $mockInner->expects($this->once())
      ->method('mail')
      ->with(
        'my_module',
        'key',
        'test@example.com',
        'en',
        ['subject' => 'Hello'],
        NULL,
        TRUE
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
