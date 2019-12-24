<?php

namespace Minter\Library;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

/**
 * Trait Http
 * @package Minter\Library
 */
Trait Http
{
    /**
     * HTTP API client
     *
     * @var Client
     */
    protected $client;

    /**
     * Set API http client
     *
     * @param Client $client
     */
    protected function setClient(Client $client): void
    {
        $this->client = $client;
    }

    /**
     * http get request
     *
     * @param string     $url
     * @param array|string|null $parameters
     * @return mixed
     * @throws \Exception
     * @throws GuzzleException
     */
    protected function get(string $url, $parameters = null)
    {
        if (!is_null($parameters)) {
          if (!is_array($parameters) && !is_string($parameters)) {
            throw new \TypeError("$parameters is not of type string or array");
          }
        }
        
        try {
            $response = $this->client->request('GET', $url, [
                'query' => $parameters
            ])->getBody();
        } catch (RequestException $exception) {
            throw $exception;
        }

        return json_decode($response);
    }

    /**
     * http post request
     *
     * @param string $url
     * @param array  $parameters
     * @return mixed
     * @throws \Exception
     * @throws GuzzleException
     */
    protected function post(string $url, array $parameters = [])
    {
        try {
            $response = $this->client->request('POST', $url, [
                'json' => $parameters
            ])->getBody();
        } catch (RequestException $exception) {
            throw $exception;
        }

        return json_decode($response);
    }
}
