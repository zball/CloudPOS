<?php
namespace CloudPOS\Bundle\StoreBundle\Tests\Controller;

use CloudPOS\Component\Testing\TestCase;
use Symfony\Component\HttpFoundation\Response;

class ProductControllerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->setUpDb();
    }

    public function testCreateReturnsNewProductWithValidParams()
    {
        $client = static::createClient();

        $client->request('POST', '/api/products', [
            'name' => 'foo',
            'unit_price' => '999'
        ]);

        $response = $client->getResponse()->getContent();
        $product = json_decode($response);

        foreach (['name', 'unit_price'] as $attribute) {
            $this->assertObjectHasAttribute($attribute, $product);
        }

        $this->assertEquals( 'foo', $product->name );
        $this->assertEquals( 999, $product->unit_price );

    }

    public function testCanReadProductsPersistedToDatabase()
    {
        $client = static::createClient();

        $client->request('POST', '/api/products', [
            'name' => 'foo',
            'unit_price' => '999'
        ]);

        $client->request('GET', '/api/products/1');

        $response = $client->getResponse()->getContent();
        $product = json_decode($response);

        $this->assertEquals(1, $product->id);
        $this->assertEquals('foo', $product->name);
        $this->assertEquals(999, $product->unit_price);
    }

    public function testCanDeletePersistedProducts()
    {
        $client = static::createClient();

        $client->request('POST', '/api/products', [
            'name' => 'foo',
            'unit_price' => '999'
        ]);

        $client->request('DELETE', '/api/products/1');

        $response = $client->getResponse()->getContent();

        $this->assertEquals(Response::HTTP_OK, $response);
    }

    public function testCanUpdateProducts()
    {
        $client = static::createClient();

        $client->request('POST', '/api/products', [
            'name' => 'foo',
            'unit_price' => '999'
        ]);

        $client->request('PUT', '/api/products/1', [
            'name' => 'bar',
            'unit_price' => '555'
        ]);

        $response = $client->getResponse()->getContent();
        $product = json_decode($response);


        $this->assertEquals('bar', $product->name);
        $this->assertEquals(555, $product->unit_price);
    }


    public function testNoResultFoundException()
    {
        $client = static::createClient();
        $client->request('GET', '/api/products/1');
        //$product = json_decode($response);

        //var_dump($product);

        $this->assertEquals('404', $client->getResponse()->getStatusCode());
    }
}