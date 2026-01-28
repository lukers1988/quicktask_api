<?php

declare(strict_types=1);

namespace App\Task\Domain\Model;

use App\Task\Domain\Enum\PriorityEnum;

readonly class TaskModel
{
    public function __construct(
        public int $id,
        public string $title,
        public ?string $description,
        public ?bool $status,
        public PriorityEnum $priority,
        public \DateTimeImmutable $createdAt,
    ) {
    }
}
