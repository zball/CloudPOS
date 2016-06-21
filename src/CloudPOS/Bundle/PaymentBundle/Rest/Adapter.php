<?php
namespace CloudPOS\Bundle\PaymentBundle\Rest;

use GuzzleHttp\Client;

class Adapter
{
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function gateway($gateway)
    {
        $class = $this->locate($gateway);

        return new $class($this->client);
    }

    /**
     * @param $gateway
     */
    protected function locate($gateway)
    {
        $class = sprintf('CloudPOS\Bundle\PaymentBundle\Rest\Gateway\%s', $gateway);

        if (class_exists($class)) {
            return $class;
        }

        throw new \InvalidArgumentException($class);
    }

}
