<?php

namespace Drupal\Tests\dependency_injection_exercise\Unit;

use Drupal\Tests\UnitTestCase;
use Drupal\dependency_injection_exercise\Service\PhotosService;
use GuzzleHttp\ClientInterface;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Drupal\Core\StringTranslation\TranslationInterface;

/**
 * Tests for the PhotosService class.
 *
 * @covers \Drupal\dependency_injection_exercise\Service\PhotosService
 * @group dependency_injection_exercise
 */
class PhotosServiceTest extends UnitTestCase {

  /**
   * Tests the getPhotos method.
   */
  public function testGetPhotos() {
    // Mock HTTP client
    $mockClient = $this->createMock(ClientInterface::class);

    // Mock Response and Stream
    $mockResponse = $this->createMock(ResponseInterface::class);
    $mockStream = $this->createMock(StreamInterface::class);
    $mockStream->method('__toString')->willReturn(json_encode([
      ['thumbnailUrl' => 'test.jpg', 'title' => 'Test Photo']
    ]));
    $mockResponse->method('getBody')->willReturn($mockStream);
    $mockClient->method('request')->willReturn($mockResponse);

    // Mock Logger
    $mockLogger = $this->createMock(LoggerInterface::class);

    // Mock Translation
    $mockTranslation = $this->createMock(TranslationInterface::class);
    $mockTranslation->method('translate')->willReturnArgument(0);

    // Service
    $service = new PhotosService($mockClient, $mockLogger, $mockTranslation);

    $result = $service->getPhotos(1);

    // Assertions
    $this->assertArrayHasKey('error', $result);
    $this->assertCount(3, $result['error']);
    $this->assertEquals('Test Photo', $result['photos'][0]['#title']);
  }
}
