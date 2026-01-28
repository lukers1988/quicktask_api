<?php

declare(strict_types=1);

namespace App\Task\Application\MessageHandler;

use App\Task\Domain\Message\ChangeTaskStatus;
use App\Task\Domain\Model\TaskModel;
use App\Task\Infrastructure\Repository\TaskRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class ChangeTaskStatusHandler
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository,
    ) {
    }

    public function __invoke(ChangeTaskStatus $changeTaskStatus): TaskModel
    {
        return $this->taskRepository->update($changeTaskStatus)->toTaskModel();
    }
}
