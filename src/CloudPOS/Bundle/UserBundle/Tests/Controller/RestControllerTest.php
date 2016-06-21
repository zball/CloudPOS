<?php
namespace CloudPOS\Bundle\UserBundle\Tests\Controller;

use CloudPOS\Component\Testing\TestCase;

class RestControllerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->setUpDb();
    }

    public function testCreateReturnsNewUserWithValidParams()
    {
        $client = static::createClient();

        $crawler = $client->request('POST', '/api/users', [
            'username' => 'foo',
            'password' => 'bar',
            'email' => 'foo@bar.com'
        ]);

        $response = $client->getResponse()->getContent();
        $user = json_decode($response);

        foreach (['username', 'password', 'email', 'salt'] as $attribute) {
            $this->assertObjectHasAttribute($attribute, $user);
        }

        $this->assertEquals('foo', $user->username);
        $this->assertEquals('foo@bar.com', $user->email);
        $this->assertTrue(
            static::$kernel->getContainer()->get('cloud_pos_user.password_encoder')->isPasswordValid(
                $user->password,
                'bar',
                $user->salt
            )
        );
    }

    public function testCanReadUsersPersistedToDatabase()
    {
        $client = static::createClient();

        $client->request('POST', '/api/users', [
            'username' => 'foo',
            'password' => 'bar',
            'email' => 'foo@bar.com'
        ]);

        $crawler = $client->request('GET', '/api/users');

        $response = $client->getResponse()->getContent();
        $users = json_decode($response);

        $this->assertCount(1, $users);
    }

    public function testCanGetCreatedUserIndividually()
    {
        $client = static::createClient();

        $client->request('POST', '/api/users', [
            'username' => 'foo',
            'password' => 'bar',
            'email' => 'foo@bar.com'
        ]);

        $crawler = $client->request('GET', '/api/users/1');
        $response = $client->getResponse()->getContent();
        $user = json_decode($response);

        $this->assertEquals('foo', $user->username);
        $this->assertEquals('foo@bar.com', $user->email);
    }

    public function testCanFilterByUsername()
    {
        $client = static::createClient();

        $client->request('POST', '/api/users', [
            'username' => 'foo1',
            'password' => 'bar',
            'email' => 'foo@bar.com'
        ]);

        $client->getResponse();

        $client->request('POST', '/api/users', [
            'username' => 'foo2',
            'password' => 'bar',
            'email' => 'foo2@bar.com'
        ]);

        $client->getResponse();

        $client->request('POST', '/api/users', [
            'username' => 'bar',
            'password' => 'bar',
            'email' => 'bar@bar.com'
        ]);
        $client->getResponse();

        $crawler = $client->request('GET', '/api/users?username=foo');
        $response = $client->getResponse()->getContent();

        $this->assertCount(2, json_decode($response));
    }
}
