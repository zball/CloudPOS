<?php
namespace CloudPOS\Bundle\CoreBundle\Tests\Controller;

use CloudPOS\Component\Testing\TestCase;

class DefaultControllerTest extends TestCase
{
    public function testDefaultResponse()
    {
        $client = static::createClient();
        $client->request('GET', '/api/');

        $response = $client->getResponse()->getContent();
        $data = json_decode($response);

        //$this->assertObjectHasAttribute('env', $data);
        //$this->assertEquals('test', $data->env);
    }
}
