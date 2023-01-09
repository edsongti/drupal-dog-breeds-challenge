<?php

namespace Drupal\dog_breeds\Services;

use Exception;

/**
 * Class BreedImageService
 * @package Drupal\mymodule\Services
 */
class BreedImageService {

    /**
     * BreedImageService constructor.
     * @param AccountInterface $currentUser
     */
    public function __construct() { }


    /**
     * @return \Drupal\Component\Render\MarkupInterface|string
     */
    public static function getBreedImages($breedSlug) {

        //call api via service API
        $api_url = "https://dog.ceo/api/breed/{$breedSlug}/images/random";
        $client = \Drupal::httpClient();

        try {

        $request = $client->get($api_url);

        $response = json_decode($request->getBody());
        if ($response->status == "success") {
            return $response->message;
        }
        } catch(Exception $e) {

        }
    }

  }