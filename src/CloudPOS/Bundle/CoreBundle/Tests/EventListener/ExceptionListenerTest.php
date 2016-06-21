<?php
namespace CloudPOS\Bundle\CoreBundle\Tests\EventListener;

use CloudPOS\Bundle\CoreBundle\EventListener\ExceptionListener;
use Mockery;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionListenerTest extends \PHPUnit_Framework_TestCase
{
    public function testEventTurnsExceptionIntoJsonResponse()
    {
        $listener = new ExceptionListener();

        $exception = new NotFoundHttpException;
        $response = new JsonResponse(['status' => 404, 'message' => 'error'], 404);

        $mock = Mockery::mock(GetResponseForExceptionEvent::class);
        $mock->shouldReceive('getException')->once()->andReturn($exception);
        $mock->shouldReceive('setResponse')->once()->andReturn($response);

        $listener->onKernelException($mock);

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('{"status":404,"message":"error"}', $response->getContent());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }
}