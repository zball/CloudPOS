<?php

namespace CloudPOS\Bundle\CoreBundle\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener
{
    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        $response = new JsonResponse();

        if ($exception instanceof HttpExceptionInterface) {
            $status = $exception->getStatusCode();
            $response->headers->replace($exception->getHeaders());
        } else {
            $status = JsonResponse::HTTP_INTERNAL_SERVER_ERROR;
        }

        $response->setData([
            'status' => $status,
            'message' => $exception->getMessage()
        ])
            ->setStatusCode($status);

        $event->setResponse($response);
    }
}