<?php
namespace CloudPOS\Bundle\PaymentBundle\Tests\Rest;

use CloudPOS\Bundle\PaymentBundle\Rest\Adapter;
use CloudPOS\Component\Testing\TestCase;
use GuzzleHttp\Client;

class AdapterTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        //$this->client = $this->container->get('cloud_pos_payment.rest_gateway.gateway');
    }

    public function testAdapterCanLocateExistentGateway()
    {
        $adapter = new Adapter(new Client());

        $this->assertInstanceOf('CloudPOS\Bundle\PaymentBundle\Rest\Gateway\Paypal', $adapter->gateway('Paypal'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testExceptionIsThrownForInvalidGateway()
    {
        $adapter = (new Adapter(new Client()))
            ->gateway('foobar');
    }
}
