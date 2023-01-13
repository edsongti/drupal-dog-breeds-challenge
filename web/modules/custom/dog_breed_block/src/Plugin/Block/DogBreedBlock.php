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
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * @var \Drupal\dog_breeds\Services\BreedImageService
   */
  protected $breedImageService;

  /**
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   * @param \Drupal\dog_breeds\Services\BreedImageService
   * Service to get a dog breed image from API.
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

    if (!$imgUrl) {
      $imgUrl = "Not foung an image for $slug dog breed";
    }

    return [
      '#markup' => '<img src="' . $imgUrl . '">',
    ];
  }

}
