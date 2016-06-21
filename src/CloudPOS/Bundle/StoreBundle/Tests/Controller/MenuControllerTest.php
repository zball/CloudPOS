<?php
namespace CloudPOS\Bundle\StoreBundle\Tests\Controller;

use CloudPOS\Component\Testing\TestCase;

class MenuControllerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setUpDb();
    }

    public function testCreateReturnsNewMenuWithValidParams()
    {
        $client = static::createClient();
        $crawler = $client->request('POST', '/api/menus', [
            'name' => 'foo',
        ]);

        $response = $client->getResponse()->getContent();
        $menu = json_decode($response);


        $this->assertEquals('foo', $menu->name);
    }

    public function testMenuGetsDataFromFixtures()
    {
        $client = static::createClient();
        $this->loadFixtures($client);

        $crawler = $client->request('GET', '/api/menus');
        $response = $client->getResponse()->getContent();
        $menus = json_decode($response);

        $this->assertCount(10, $menus);
    }

    public function testMenuThrows404WhenItemDoesNotExist()
    {
        $client = static::createClient();
        $client->request('GET', '/api/menus/12');

        $response = $client->getResponse();

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('{"status":404,"message":"The menu you queried for does not exist."}', $response->getContent());
    }

    protected function loadFixtures($client)
    {
        foreach (range(1, 10) as $index) {
            $crawler = $client->request('POST', '/api/menus', [
                'name' => 'foo' + $index,
            ]);
        }
    }
}