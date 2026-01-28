<?php

declare(strict_types=1);

namespace App\Task\Application\Service;

use App\Task\Domain\Entity\Task;
use App\Task\Domain\Message\ChangeTaskStatus;
use App\Task\Domain\Message\CreateTask;
use App\Task\Domain\Model\TaskModel;
use App\Task\Infrastructure\Repository\TaskRepositoryInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final readonly class TaskService
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository,
        private MessageBusInterface $messageBus,
    ) {
    }

    /**
     * @return array<TaskModel>
     */
    public function getTasks(): array
    {
        $collection = $this->taskRepository->collection();

        return array_map(
            static fn (Task $task) => $task->toTaskModel(),
            $collection
        );
    }

    public function createTask(CreateTask $createTask): ?TaskModel
    {
        $envelope = $this->messageBus->dispatch($createTask);

        $handledStamp = $envelope->last(HandledStamp::class);

        if (!$handledStamp instanceof HandledStamp) {
            return null;
        }

        return $handledStamp->getResult();
    }

    public function changeTaskStatus(ChangeTaskStatus $changeTaskStatus): ?TaskModel
    {
        $envelope = $this->messageBus->dispatch($changeTaskStatus);

        $handledStamp = $envelope->last(HandledStamp::class);

        if (!$handledStamp instanceof HandledStamp) {
            return null;
        }

        return $handledStamp->getResult();
    }
}
