<?php

declare(strict_types=1);

namespace App\Task\Domain\Message;

readonly class ChangeTaskStatus
{
    public function __construct(
        public readonly int $id,
        public readonly ?bool $status,
    ) {
    }
}
