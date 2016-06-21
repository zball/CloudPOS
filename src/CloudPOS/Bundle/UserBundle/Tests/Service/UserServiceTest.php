<?php
namespace CloudPOS\Bundle\UserBundle\Tests\Entity;

use CloudPOS\Bundle\UserBundle\Service\UserService;
use CloudPOS\Component\Testing\TestCase;
use CloudPOS\Component\Validator\Validator;
use Mockery;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;

class UserServiceTest extends TestCase
{

    public function testValidUserIsCreatedWithAppropriateArguments()
    {
        $data = [
            'username' => 'foo',
            'email' => 'foo@bar.baz',
            'password' => 'foobar'
        ];

        $request = new Request([], $data);

        $encoder = Mockery::mock(BCryptPasswordEncoder::class);
        $encoder
            ->shouldReceive('encodePassword')
            ->once()
            ->andReturn('encoded');

        $validator = Mockery::mock(Validator::class);
        $validator->shouldReceive('validate')->once()->andReturn(true);

        $service = new UserService($encoder, $validator);
        $user = $service->createUser($request);

        $this->assertInstanceOf('CloudPOS\Bundle\UserBundle\Entity\User', $user);
        $this->assertEquals('foo', $user->getUsername());
        $this->assertEquals('encoded', $user->getPassword());
    }
}