<?php

namespace CloudPOS\Bundle\StoreBundle\Tests\Controller;

use CloudPOS\Component\Testing\TestCase;


class CheckoutControllerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->setUpDb();
    }

    public function testCheckoutCreatesAndReturnsAnInvoice()
    {
        $client = static::createClient();

        $client->request('POST', '/api/carts');
        $client->request('POST', '/api/products', [
            'name' => 'Foo',
            'unit_price' => '500'
        ]);
        $client->request('POST', '/api/carts/1/products/1', ['quantity' => 1]);

        $client->request('POST', '/api/checkout', [
            'cart_id' => 1
        ]);

        $response = $client->getResponse()->getContent();
        $invoice = json_decode($response);

        $this->assertJson($response);
        $this->assertEquals('processing', $invoice->status);
        $this->assertEquals('closed', $invoice->cart->status);
        $this->assertNotEmpty($invoice->created_at);


    }


}
