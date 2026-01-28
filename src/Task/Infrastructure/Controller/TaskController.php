<?php

declare(strict_types=1);

namespace App\Task\Infrastructure\Controller;

use App\Task\Application\Service\TaskService;
use App\Task\Domain\Message\ChangeTaskStatus;
use App\Task\Domain\Message\CreateTask;
use App\Task\Infrastructure\Controller\DTO\ChangeTaskStatusDTO;
use App\Task\Infrastructure\Controller\DTO\CreateTaskDTO;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route(path: '/api/task', name: 'task')]
final class TaskController
{
    public function __construct(
        private readonly TaskService $taskService,
    ) {
    }

    #[Route(name: 'task_collection', methods: ['GET'])]
    public function collection(): JsonResponse
    {
        return new JsonResponse(['tasks' => $this->taskService->getTasks()], JsonResponse::HTTP_OK);
    }

    #[Route(name: 'create_task', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] CreateTaskDTO $createTaskDTO,
    ): JsonResponse {
        try {
            $createTask = new CreateTask(
                title: $createTaskDTO->title,
                description: $createTaskDTO->description,
                status: $createTaskDTO->status,
                priority: $createTaskDTO->priority,
            );

            $task = $this->taskService->createTask($createTask);

            return new JsonResponse(
                [
                    'message' => 'Zadanie zostało utworzone pomyślnie.',
                    'task' => $task,
                ],
                JsonResponse::HTTP_CREATED,
            );
        } catch (\Throwable $e) {
            return new JsonResponse(
                ['error' => 'Błąd podczas tworzenia zadania.'],
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
            );
        }
    }

    #[Route(path: '/{id}/change_status', name: 'change_status', methods: ['PATCH'])]
    public function update(
        int $id,
        #[MapRequestPayload] ChangeTaskStatusDTO $changeTaskStatusDTO,
    ): JsonResponse {
        try {
            $changeTaskStatus = new ChangeTaskStatus(
                id: $id,
                status: $changeTaskStatusDTO->status,
            );

            $taskUpdated = $this->taskService->changeTaskStatus($changeTaskStatus);

            return new JsonResponse(
                [
                    'message' => 'Status zadania został zaktualizowany.',
                    'task' => $taskUpdated,
                ],
                JsonResponse::HTTP_OK,
            );
        } catch (\Throwable $e) {
            return new JsonResponse(
                ['error' => 'Błąd podczas aktualizacji statusu zadania.'],
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
            );
        }
    }
}
