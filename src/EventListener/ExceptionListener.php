<?php

namespace App\EventListener;

use App\Error\Filter\FilterValidationException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

final class ExceptionListener
{
    #[AsEventListener]
    public function onExceptionEvent(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        if (!($exception instanceof FilterValidationException)) {
            return;
        }
        $response = new JsonResponse([
            'success'=>false,
            'message'=>$exception->getMessage(),
            'path'=>$exception->path,
        ], 422, ['content-type'=>'application/problem+json']);

        $event->setResponse($response);
    }
}
