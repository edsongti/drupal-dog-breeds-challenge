<?php

namespace Drupal\dog_breeds\Services;

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
   * The cache backend service.
   *
   * @var \Drupal\Core\Cache\CacheBackendInterface
   */
  protected $cacheBackend;

  /**
   * {@inheritdoc}
   */
  public function __construct(
    ClientInterface $http_client,
    LoggerChannelFactoryInterface $logger_factory,
    CacheBackendInterface $cache_default
    ) {
    $this->httpClient = $http_client;
    $this->loggerFactory = $logger_factory->get('dog_breeds');
    $this->cacheBackend = $cache_default;
  }

  /**
   * Method to get a dog breed image from the API.
   *
   * @return string|null
   *   Will return an URL or NULL.
   *
   * @throws Exception
   *   Will log error in the system.
   */
  public function getBreedImages($breedSlug): string|NULL {
    $breedSlugCached = $this->cacheBackend->get($breedSlug);

    if ($breedSlugCached) {
      return $breedSlugCached->data;
    }

    $breedSlugNormalized = $this->normalizeBreedSlug($breedSlug);

    $api_url = "https://dog.ceo/api/breed/{$breedSlugNormalized}/images/random";

    try {
      $request = $this->httpClient->get($api_url);
      $response = json_decode($request->getBody());
      $img_url = isset($response->message) ? $response->message : NULL;
      if ($img_url) {
        $this->cacheBackend->set($breedSlug, $img_url, CacheBackendInterface::CACHE_PERMANENT);
        return $response->message;
      }
      return NULL;
    }
    catch (\Exception $e) {
      $this->loggerFactory->error($e->getMessage());
    }
  }

  /**
   * Normalize Breed Slug.
   *
   *  Convert to lower case, trim and replace "-" for "/".
   *
   * @return string
   *   Return a slug normalized.
   */
  private function normalizeBreedSlug($breedSlug): string {
    // Lower case and trim.
    $breedSlugLowerCase = strtolower(trim($breedSlug));

    // Replace "-" to "/".
    if (str_contains($breedSlugLowerCase, "-")) {
      return str_replace("-", "/", $breedSlug);
    }

    return $breedSlug;
  }

}
