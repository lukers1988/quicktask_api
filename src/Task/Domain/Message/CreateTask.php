<?php

declare(strict_types=1);

namespace App\Task\Domain\Message;

use App\Task\Domain\Enum\PriorityEnum;

readonly class CreateTask
{
    public function __construct(
        public readonly string $title,
        public readonly string $description,
        public readonly bool $status,
        public readonly PriorityEnum $priority,
    ) {
    }
}
