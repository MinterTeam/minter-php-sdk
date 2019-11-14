<?php
namespace Minter\Library;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

/**
 * Trait Http
 * @package Minter\Library
 */
Trait Http
{
    /**
     * guzzle client
     *
     * @Client
     */
    protected $client;

    /**
     * Set base API url.
     *
     * @param string $url
     */
    public function setApiUrl(string $url): void
    {
        $config = [
            'base_uri' => $url,
            'connect_timeout' => 15.0,
            'timeout' => 30.0,
        ];
        
        if ($this->client instanceof \GuzzleHttp\Client)
        {
            $config = $this->client->getConfig();
            $config['base_uri'] = $url;
        }
        
        $this->setClient(new \GuzzleHttp\Client($config));
    }

   /**
   * Set client
   *
   * @param Client $client
   */
    protected function setClient(\GuzzleHttp\Client $client): void
    {
      $this->client = $client;
    }
    
    /**
     * http get request
     *
     * @param string $url
     * @param array|null $parameters
     * @return mixed
     * @throws \Exception
     */
    protected function get(string $url, array $parameters = null)
    {
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
     * @param array $parameters
     * @return mixed
     * @throws \Exception
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
