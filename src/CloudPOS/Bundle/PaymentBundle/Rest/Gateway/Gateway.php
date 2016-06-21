<?php
namespace CloudPOS\Bundle\PaymentBundle\Rest\Gateway;

use GuzzleHttp\Client;

abstract class Gateway implements GatewayInterface
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param $method
     * @param $endpoint
     * @param array $data
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function createRequest($method, $endpoint, $data = [])
    {
        $response = $this->client->request(
            $method,
            $endpoint,
            $data
        );

        return $response;
    }

    /**
     * @param $endpoint
     * @param $data
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function get($endpoint, $data)
    {
        return $this->createRequest('GET', $endpoint, $data);
    }

    /**
     * @param $endpoint
     * @param $data
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function post($endpoint, $data)
    {
        return $this->createRequest('POST', $endpoint, $data);
    }

    /**
     * @param $endpoint
     * @param $data
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function put($endpoint, $data)
    {
        return $this->createRequest('PUT', $endpoint, $data);
    }

    /**
     * @param $endpoint
     * @param $data
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function delete($endpoint, $data)
    {
        return $this->createRequest('DELETE', $endpoint, $data);
    }
}
