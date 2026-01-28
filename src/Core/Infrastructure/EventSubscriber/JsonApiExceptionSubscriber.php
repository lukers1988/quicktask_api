<?php
declare(strict_types=1);

namespace App\Core\Infrastructure\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

final readonly class JsonApiExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            ExceptionEvent::class => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $request = $event->getRequest();

        if (!str_starts_with($request->getPathInfo(), '/api/')) {
            return;
        }

        $exception = $event->getThrowable();

        $response = new \Symfony\Component\HttpFoundation\JsonResponse(
            [
                'error' => [
                    [
                        'status' => $exception->getCode() ?: 500,
                        'detail' => 'Błąd podczas przetwarzania żadania.',
                        'type' => (new \ReflectionClass($exception))->getShortName()
                    ],
                ],
            ],
            (int) $exception->getCode() ?: 500
        );
        
        $event->setResponse($response);
    }
}