<?php
// star_wars_films/tests/StarWarsFilmsTest.php

namespace Drupal\Tests\starwars_movies\Unit;

use Drupal\starwars_movies\Controller\FilmsList;
use Drupal\Tests\UnitTestCase;
use GuzzleHttp\ClientInterface;
use Prophecy\Argument;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
/**
 * Defines a test for the fetchStarWarsFilms function.
 *
 * @group starwars_movies
 */
class TestStarWarsFilmsAPI extends UnitTestCase {

  protected $filmsList;

  protected function setUp(): void {
    parent::setUp();

    // Create a mock HTTP client using GuzzleHttp-PHPUnit.
    $mock = new MockHandler([
      new Response(200, [], json_encode([
        'results' => [
          [
            'title' => 'Film 1',
            'episode_id' => 1,
            'director' => 'Director 1',
            'producer' => 'Producer 1',
            'release_date' => '2023-01-01',
          ],
          [
            'title' => 'Film 2',
            'episode_id' => 2,
            'director' => 'Director 2',
            'producer' => 'Producer 2',
            'release_date' => '2023-01-02',
          ],
          [
            'title' => 'Film 3',
            'episode_id' => 3,
            'director' => 'Director 3',
            'producer' => 'Producer 3',
            'release_date' => '2023-01-03',
          ],
        ],
      ])),
    ]);

    $handler = HandlerStack::create($mock);
    $httpClient = new Client(['handler' => $handler]);

    $this->filmsList = new FilmsList($httpClient);
  }

  public function testList() {
    // Call the 'list' method and get the result.
    $films = $this->filmsList->list();

    // Assert that you get the expected number of films.
    $this->assertCount(3, $films, 'Expected 3 films.');

    // You can add more specific assertions on the $films array if needed.
  }

}
