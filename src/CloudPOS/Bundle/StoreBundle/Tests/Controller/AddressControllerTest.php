<?php
namespace CloudPOS\Bundle\StoreBundle\Tests\Controller;

use CloudPOS\Component\Testing\TestCase;

class AddressConrtollerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setUpDb();

        $client = static::createClient();

        $client->request('POST', '/api/users', [
            'username' => 'foo',
            'password' => 'bar',
            'email' => 'foo@bar.com'
        ]);


        $client->request('POST', '/api/users/1/addresses', [
            'address' => '123 Foobar Circle',
            'country_code' => 'US',
            'postal_code' => '12345',
            'city' => 'Foo',
            'state' => 'Bar',
            'name' => 'Home',
            'default' => true,
        ]);
    }

    public function testCreateReturnsNewAddressWithValidParams()
    {
        $client = static::createClient();
        $client->request('POST', '/api/users/1/addresses', [
            'address' => '123 Foobar Circle',
            'country_code' => 'US',
            'postal_code' => '12345',
            'city' => 'Foo',
            'state' => 'Bar',
            'name' => 'Home',
            'default' => true,
        ]);

        $response = $client->getResponse()->getContent();
        $address = json_decode($response);

        $this->assertEquals('123 Foobar Circle', $address->address);
        $this->assertEquals('US', $address->country_code);
        $this->assertEquals('12345', $address->postal_code);
        $this->assertEquals('Foo', $address->city);
        $this->assertEquals('Bar', $address->state);
        $this->assertEquals('Home', $address->name);
        $this->assertEquals(true, $address->default);
    }

    public function testCanRetreiveAllExistingAddresses()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/api/users/1/addresses');

        $response = $client->getResponse()->getContent();
        $address = json_decode($response);
        $address = reset($address);

        $this->assertEquals('123 Foobar Circle', $address->address);
        $this->assertEquals('US', $address->country_code);
        $this->assertEquals('12345', $address->postal_code);
        $this->assertEquals('Foo', $address->city);
        $this->assertEquals('Bar', $address->state);
        $this->assertEquals('Home', $address->name);
        $this->assertEquals(true, $address->default);
    }

    public function testCanRetreiveSpecificAddress()
    {
        $client = static::createClient();
        $client->request('GET', '/api/users/1/addresses/1');

        $response = $client->getResponse()->getContent();
        $address = json_decode($response);

        $this->assertEquals('123 Foobar Circle', $address->address);
        $this->assertEquals('US', $address->country_code);
        $this->assertEquals('12345', $address->postal_code);
        $this->assertEquals('Foo', $address->city);
        $this->assertEquals('Bar', $address->state);
        $this->assertEquals('Home', $address->name);
        $this->assertEquals(true, $address->default);
    }

    public function testNewAddressAsDefaultOverridesPreviousDefault()
    {
        $client = static::createClient();
        $client->request('POST', '/api/users/1/addresses', [
            'address' => '123 Foobar Circle',
            'country_code' => 'US',
            'postal_code' => '12345',
            'city' => 'Foo',
            'state' => 'Bar',
            'name' => 'Home',
            'default' => true,
        ]);

        $response = $client->getResponse()->getContent();
        $new = json_decode($response);
        unset($response);

        $client->request('GET', '/api/users/1/addresses/1');

        $response = $client->getResponse()->getContent();
        $original = json_decode($response);

        $this->assertFalse($original->default);
        $this->assertTrue($new->default);

    }

    public function testUpdatingAddressAsDefaultOverridesPreviousDefault()
    {
        $client = static::createClient();
        $client->request('POST', '/api/users/1/addresses', [
            'address' => '123 Foobar Circle',
            'country_code' => 'US',
            'postal_code' => '12345',
            'city' => 'Foo',
            'state' => 'Bar',
            'name' => 'Home',
            'default' => false,
        ]);

        $response = $client->getResponse()->getContent();
        $new = json_decode($response);
        unset($response);

        $this->assertFalse($new->default);

        $client->request('PUT', '/api/users/1/addresses/2', ['default' => true]);
        $response = $client->getResponse()->getContent();
        unset($response);


        $client->request('GET', '/api/users/1/addresses/2');
        $response = $client->getResponse()->getContent();
        $updated = json_decode($response);

        //$this->assertTrue($updated->default);
    }
}
