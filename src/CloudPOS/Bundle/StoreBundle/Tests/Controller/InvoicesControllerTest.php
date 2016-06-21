<?php

namespace CloudPOS\Bundle\StoreBundle\Tests\Controller;

use CloudPOS\Component\Testing\TestCase;


class InvoicesControllerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->setUpDb();
    }

    public function testGetAllInvoices()
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


        $client->request('POST', '/api/carts');
        $client->request('POST', '/api/carts/2/products/1', ['quantity' => 1]);
        $client->request('POST', '/api/checkout', [
            'cart_id' => 2
        ]);

        $client->request('POST', '/api/carts');
        $client->request('POST', '/api/carts/3/products/1', ['quantity' => 1]);
        $client->request('POST', '/api/checkout', [
            'cart_id' => 3
        ]);

        $client->request('PUT', '/api/invoices/1/close');

        $client->request('GET', '/api/invoices');
        $response = $client->getResponse()->getContent();
        $invoicesAll = json_decode($response);

        $client->request('GET', '/api/invoices', ['status' => 'closed']);
        $response = $client->getResponse()->getContent();
        $invoicesClosed = json_decode($response);

        $client->request('GET', '/api/invoices', ['status' => 'processing']);
        $response = $client->getResponse()->getContent();
        $invoicesProcessing = json_decode($response);

        $this->assertCount(3, $invoicesAll);
        $this->assertCount(1, $invoicesClosed);
        $this->assertCount(2, $invoicesProcessing);


    }

    public function testGetInvoiceById()
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
        $client->request('GET', '/api/invoices/1');

        $response = $client->getResponse()->getContent();
        $invoice = json_decode($response);

        $this->assertEquals('processing', $invoice->status);
    }

    public function testClosingOfInvoice()
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
        $client->request('PUT', '/api/invoices/1/close');

        $response = $client->getResponse()->getContent();
        $invoice = json_decode($response);

        $this->assertEquals('closed', $invoice->status);
    }
}