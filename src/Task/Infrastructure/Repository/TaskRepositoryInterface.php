<?php

declare(strict_types=1);

namespace App\Task\Infrastructure\Repository;

use App\Task\Domain\Entity\Task;
use App\Task\Domain\Message\ChangeTaskStatus;
use App\Task\Domain\Message\CreateTask;

interface TaskRepositoryInterface
{
    /**
     * @return array<Task>
     */
    public function collection(): array;

    public function save(CreateTask $createTask): Task;

    public function update(ChangeTaskStatus $changeTaskStatus): Task;
}
