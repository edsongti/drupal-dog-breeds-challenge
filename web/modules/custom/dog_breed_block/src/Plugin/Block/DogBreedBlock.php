<?php

namespace Drupal\dog_breed_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\dog_breeds\Services\BreedImageService;

/**
 * Provides a 'Dog Breed' Block.
 *
 * @Block(
 *   id = "dog_breed_custom_block",
 *   admin_label = @Translation("Dog Breed Custom Block"),
 *   category = @Translation("Dog Breed"),
 * )
 */
class DogBreedBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Service to get/set configs values.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Service to get a dog breed image from API.
   *
   * @var \Drupal\dog_breeds\Services\BreedImageService
   */
  protected $breedImageService;

  /**
   * {@inheritdoc}
   *
   * @param array $configuration
   *   Array with config values.
   * @param string $plugin_id
   *   Plugin Id.
   * @param mixed $plugin_definition
   *   Service to get/set configs values.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   Service to get/set configs values.
   * @param \Drupal\dog_breeds\Services\BreedImageService $breeds_img_service
   *   Service to get a dog breed image from API.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    ConfigFactoryInterface $config_factory,
    BreedImageService $breeds_img_service
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->configFactory = $config_factory;
    $this->breedImageService = $breeds_img_service;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('config.factory'),
      $container->get('dog_breeds.breeds_img_service'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $config = $this->configFactory->get('dog_breed_block.settings');
    $slug = $config->get('dog_breed_slug');
    $imgUrl = $this->breedImageService->getBreedImages($slug);

    if ($imgUrl) {
      $markUp = '<img src="' . $imgUrl . '">';
    } else {
      $markUp = $this->t("<p>Not foung an image for $slug dog breed.<p>" );
    }

    return [
      '#markup' => $markUp,
    ];
  }

}
