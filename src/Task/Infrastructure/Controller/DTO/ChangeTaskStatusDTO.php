<?php

declare(strict_types=1);

namespace App\Task\Infrastructure\Controller\DTO;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class ChangeTaskStatusDTO
{
    public function __construct(
        #[Assert\Type(
            type: 'bool',
            message: 'Status musi być wartością logiczną.',
        )]
        public bool $status,
    ) {
    }
}
