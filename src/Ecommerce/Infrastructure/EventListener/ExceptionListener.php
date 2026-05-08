<?php

namespace App\Ecommerce\Infrastructure\EventListener;

use App\Ecommerce\Domain\Exception\DomainExceptionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $response = new JsonResponse();

        // Si c'est une exception métier, on retourne un message d'erreur clair
        if ($exception instanceof DomainExceptionInterface) {
            $response->setData(['error' => $exception->getMessage()]);
            $response->setStatusCode(JsonResponse::HTTP_BAD_REQUEST);// Bad Request pour les erreurs métier
        } elseif ($exception instanceof HttpExceptionInterface) {
            // Pour les exceptions HTTP, on utilise le code de statut de l'exception
            $response->setData(['error' => $exception->getMessage()]);
            $response->setStatusCode($exception->getStatusCode());
        } else {
            // Pour les autres exceptions, on retourne une erreur générique
            $response->setData(['error' => 'Une erreur interne est survenue.']);
            $response->setStatusCode(JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        $event->setResponse($response);
    }
}