<?php

declare(strict_types=1);

namespace App\Task\Application\MessageHandler;

use App\Task\Domain\Message\CreateTask;
use App\Task\Domain\Model\TaskModel;
use App\Task\Infrastructure\Repository\TaskRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class CreateTaskHandler
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository,
    ) {
    }

    public function __invoke(CreateTask $createTask): TaskModel
    {
        return $this->taskRepository->save($createTask)->toTaskModel();
    }
}
