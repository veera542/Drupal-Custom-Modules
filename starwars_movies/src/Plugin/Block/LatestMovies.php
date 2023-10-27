<?php

namespace Drupal\starwars_movies\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use GuzzleHttp\ClientInterface;
use Drupal\Component\Serialization\Json;

/**
 * Provides Movies Block.
 *
 * @Block(
 *   id = "latest_movies",
 *   admin_label = @Translation("Latest Movies"),
 *   category = @Translation("Latest Movies"),
 * )
 */
class LatestMovies extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The HTTP client.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;

  /**
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   * @param \GuzzleHttp\ClientInterface $http_client
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ClientInterface $http_client) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->httpClient = $http_client;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('http_client')
    );
  }

  public function build() {
    $request = $this->httpClient->get("https://swapi.dev/api/films/");
    $response = $request->getBody()->getContents();
    $result = Json::decode($response);

    if (!isset($result['results'])) {
      throw new \Exception('The SWAPI response is missing the "results" key.');
    }

    // Initialize an array to store the film details.
    $films = [];
    for ($i = 0; $i < 3; $i++) {
      $film = $result['results'][$i];
      $filmData = [
        'title' => $film['title'],
        'episode_id' => $film['episode_id'],
        'director' => $film['director'],
        'producer' => $film['producer'],
        'release_date' => date('F j, Y', strtotime($film['release_date'])),
      ];

      $films[] = $filmData;
    }

    return [
      '#theme' => 'latest_movies',
      '#films' => $films,
    ];
  }
}
