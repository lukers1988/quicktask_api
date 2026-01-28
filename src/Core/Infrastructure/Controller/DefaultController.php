<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route(path: '/')]
final readonly class DefaultController
{
    public function __invoke(): JsonResponse
    {
        return new JsonResponse(
            data: [
                'notFound' => 'notFound',
            ],
            status: 404
        );
    }
}
