<?php

namespace Drupal\starwars_movies\Controller;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Controller\ControllerBase;
use GuzzleHttp\ClientInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides films responses for the starwars_movies module.
 */
class FilmsList extends ControllerBase {

  /**
   * The HTTP client.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;

  /**
   * Constructs a FilmsList object.
   *
   * @param \GuzzleHttp\ClientInterface $http_client
   *   The HTTP client.
   */
  public function __construct(ClientInterface $http_client) {
    $this->httpClient = $http_client;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('http_client')
    );
  }

  /**
   * Returns a list of films.
   *
   * @return array
   *   A simple renderable array.
   */
  public function list() {
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

    return $films;
  }
}
