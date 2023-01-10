<?php

namespace Drupal\dog_breeds\Services;

use Drupal\Component\DependencyInjection\ContainerInterface;
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

    // Call api via service API.
    $api_url = "https://dog.ceo/api/breed/{$breedSlug}/images/random";

    try {
      $request = $this->httpClient->get($api_url);

      $response = json_decode($request->getBody());
      if ($response->status == "success") {
        return $response->message;
      }
    }
    catch (\Exception $e) {

      $this->loggerFactory->info($e->getMessage());
    }
  }

}
