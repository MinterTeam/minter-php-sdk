<?php
namespace Minter\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

Trait Http
{
    /**
     * guzzle client
     *
     * @Client
     */
    private $client;

    /**
     * set base api url
     *
     * @param string $url
     */
    private function setApiUrl(string $url) : void {

        $this->client = new Client([
            'base_uri' => $url
        ]);
    }

    /**
     * http get request
     *
     * @param string $url
     * @param array|null $parameters
     * @return mixed
     * @throws \Exception
     */
    private function get(string $url, array $parameters = null)
    {
        try {

            $response = $this->client->request('GET', $url, [
                'query' => $parameters
            ])->getBody();

        } catch (RequestException $exception) {
            throw new \Exception($exception->getMessage());
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
    private function post(string $url, array $parameters)
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