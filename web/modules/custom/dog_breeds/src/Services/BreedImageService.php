<?php

namespace Drupal\dog_breeds\Services;

use Drupal\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use GuzzleHttp\ClientInterface;

/**
 * Class BreedImageService.
 *
 * Service to get a dog breed image from the API.
 *
 * @package Drupal\dog_breeds\Services
 */
class BreedImageService {

  /**
   * Guzzle\Client instance.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;

  /**
   * Logger Factory.
   *
   * @var \Drupal\Core\Logger\LoggerChannelFactoryInterface
   */
  protected $loggerFactory;

  /**
   * {@inheritdoc}
   */
  public function __construct(ClientInterface $http_client, LoggerChannelFactoryInterface $logger_factory) {
    $this->httpClient = $http_client;
    $this->loggerFactory = $logger_factory->get('dog_breeds');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
          $container->get('http_client'),
          $container->get('logger.factory')
      );
  }

  /**
   * Method to get a dog breed image from the API.
   *
   * @return array|log
   *   Will return an array or a log in the system.
   */
  public function getBreedImages($breedSlug) {
    $breedSlugCached = \Drupal::cache()->get($breedSlug);
    if ($breedSlugCached) {
        return $breedSlugCached->data;
    }

    // \Drupal::cache()->get($breedSlug, $data, CacheBackendInterface::CACHE_PERMANENT);

    // Call api via service API.
    $api_url = "https://dog.ceo/api/breed/{$breedSlug}/images/random";

    try {
      $request = $this->httpClient->get($api_url);
      $response = json_decode($request->getBody());
      $img_url = isset($response->message) ? $response->message : NULL;
      if ($img_url) {
        \Drupal::cache()->set($breedSlug, $img_url, CacheBackendInterface::CACHE_PERMANENT);
        return $response->message;
      }
    }
    catch (\Exception $e) {
      $this->loggerFactory->info($e->getMessage());
    }
  }

}
