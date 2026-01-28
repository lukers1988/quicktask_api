<?php

declare(strict_types=1);

namespace App\Task\Domain\Enum;

enum PriorityEnum: int
{
    case LOW = 1;
    case MEDIUM = 2;
    case HIGH = 3;
}
