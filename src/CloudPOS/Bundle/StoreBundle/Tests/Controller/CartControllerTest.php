<?php
namespace CloudPOS\Bundle\StoreBundle\Tests\Controller;

use CloudPOS\Component\Testing\TestCase;

class CartControllerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->setUpDb();
    }

    public function testCreateReturnsNewCartWithValidParams()
    {
        $client = static::createClient();

        $client->request('POST', '/api/carts');

        $response = $client->getResponse()->getContent();
        $cart = json_decode($response);

        foreach (['created_at', 'expires_at', 'cart_total'] as $attribute) {
            $this->assertObjectHasAttribute($attribute, $cart);
        }

        $this->assertGreaterThanOrEqual(0, $cart->cart_total);

    }

    public function testCanReadCartsPersistedToDatabase()
    {
        $client = static::createClient();

        $client->request('POST', '/api/carts');

        $client->request('GET', '/api/carts/1');

        $response = $client->getResponse()->getContent();
        $cart = json_decode($response);


        $this->assertEquals(1, $cart->id);
        $this->assertEquals(0, $cart->cart_total);
    }

    public function testCanAddProductToCart()
    {
        $client = static::createClient();

        $client->request('POST', '/api/carts');
        $client->request('POST', '/api/products', [
            'name' => 'Foo',
            'unit_price' => '500'
        ]);
        $client->request('POST', '/api/products', [
            'name' => 'Bar',
            'unit_price' => '100'
        ]);
        $client->request('POST', '/api/carts/1/products/1', ['quantity' => 1]);
        $client->request('POST', '/api/carts/1/products/2', ['quantity' => 2]);

        $response = $client->getResponse()->getContent();
        $cart = json_decode($response);

        $this->assertCount(2, $cart->cart_items);
        $this->assertEquals(700, $cart->cart_total);
        $this->assertEquals('Foo', $cart->cart_items[0]->product->name);
        $this->assertEquals('Bar', $cart->cart_items[1]->product->name);
    }

    public function testQuantityOfCartItemsOfSimilarProductsAreOnlyIncreased()
    {
        $client = static::createClient();

        $client->request('POST', '/api/carts');
        $client->request('POST', '/api/products', [
            'name' => 'Foo',
            'unit_price' => '500'
        ]);
        $client->request('POST', '/api/carts/1/products/1', ['quantity' => 1]);
        $client->request('POST', '/api/carts/1/products/1', ['quantity' => 2]);

        $response = $client->getResponse()->getContent();
        $cart = json_decode($response);

        $this->assertCount(1, $cart->cart_items);
        $this->assertEquals(1500, $cart->cart_total);
        $this->assertEquals('Foo', $cart->cart_items[0]->product->name);
        $this->assertEquals(3, $cart->cart_items[0]->quantity);
    }

    public function testQuantityOfCartItemsOfSimilarProductsAreIncreasedByOneIfNotSpecified()
    {
        $client = static::createClient();

        $client->request('POST', '/api/carts');
        $client->request('POST', '/api/products', [
            'name' => 'Foo',
            'unit_price' => '500'
        ]);
        $client->request('POST', '/api/carts/1/products/1');
        $client->request('POST', '/api/carts/1/products/1');
        $client->request('POST', '/api/carts/1/products/1');
        $client->request('POST', '/api/carts/1/products/1');

        $response = $client->getResponse()->getContent();
        $cart = json_decode($response);

        $this->assertCount(1, $cart->cart_items);
        $this->assertEquals(2000, $cart->cart_total);
        $this->assertEquals('Foo', $cart->cart_items[0]->product->name);
        $this->assertEquals(4, $cart->cart_items[0]->quantity);
    }

    public function testCanRemoveCartItem()
    {
        $client = static::createClient();

        $client->request('POST', '/api/carts');
        $client->request('POST', '/api/products', [
            'name' => 'Foo',
            'unit_price' => '500'
        ]);
        $client->request('POST', '/api/carts/1/products/1');
        $client->request('POST', '/api/carts/1/products/1');
        $client->request('POST', '/api/carts/1/products/1');
        $client->request('DELETE', '/api/carts/1/products/1');

        $client->request('GET', '/api/carts/1');
        $response = $client->getResponse()->getContent();
        $cart = json_decode($response);

        $this->assertCount(0, $cart->cart_items);
        $this->assertEquals(0, $cart->cart_total);
    }

    public function testCanUpdateProductAlreadyInCart()
    {
        $client = static::createClient();

        $client->request('POST', '/api/carts');
        $client->request('POST', '/api/products', [
            'name' => 'Foo',
            'unit_price' => '500'
        ]);
        $client->request('POST', '/api/products', [
            'name' => 'Bar',
            'unit_price' => '200'
        ]);
        $client->request('POST', '/api/carts/1/products/1');
        $client->request('POST', '/api/carts/1/products/2');
        $client->request('PUT', '/api/carts/1/products/1', ['quantity' => 3]);
        $client->request('POST', '/api/carts/1/products/1');

        $client->request('GET', '/api/carts/1');
        $response = $client->getResponse()->getContent();
        $cart = json_decode($response);

        $this->assertCount(2, $cart->cart_items);
        $this->assertEquals(4, $cart->cart_items[0]->quantity);
        $this->assertEquals(1, $cart->cart_items[1]->quantity);
        $this->assertEquals(2200, $cart->cart_total);
        $this->assertNotEmpty($cart->updated_at);

    }

    public function test404()
    {
        $client = static::createClient();
        $client->request('GET', 'api/carts/10');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }
}
