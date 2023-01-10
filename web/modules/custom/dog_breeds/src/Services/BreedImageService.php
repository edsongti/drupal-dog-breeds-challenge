<?php

namespace Drupal\dog_breeds\Services;

use Drupal\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use GuzzleHttp\ClientInterface;
use Exception;

/**
 * Class BreedImageService
 * @package Drupal\dog_breeds\Services
 */
class BreedImageService {

    /**
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
     * BreedImageService constructor.
     * @param \GuzzleHttp\ClientInterface $http_client
     *   A Guzzle client object.
     * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface$loggerFactory
     */
    public function __construct(ClientInterface $http_client, LoggerChannelFactoryInterface $loggerFactory) {
        $this->httpClient = $http_client;
        $this->loggerFactory = $loggerFactory->get('dog_breeds');
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
     * @return array|log
     */
    public function getBreedImages($breedSlug) {

        //call api via service API
        $api_url = "https://dog.ceo/api/breed/{$breedSlug}/images/random";

        try {
            $request = $this->httpClient->get($api_url);

            $response = json_decode($request->getBody());
            if ($response->status == "success") {
                return $response->message;
            }
        } catch(Exception $e) {
            $this->loggerFactory->info($e->getMessage());
        }
    }

  }