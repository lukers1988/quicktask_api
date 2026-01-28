<?php

declare(strict_types=1);

namespace App\Task\Infrastructure\Controller\DTO;

use App\Task\Domain\Enum\PriorityEnum;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateTaskDTO
{
    public function __construct(
        #[Assert\NotBlank(
            message: 'Tytuł jest wymagany.',
        )]
        #[Assert\Length(
            min: 1,
            minMessage: 'Tytuł musi mieć co najmniej {{ limit }} znaków.',
            max: 30,
            maxMessage: 'Tytuł nie może być dłuższy niż {{ limit }} znaków.',
        )]
        #[Assert\Type(
            type: 'string',
            message: 'Tytuł musi być ciągiem znaków.',
        )]
        public string $title,
        #[Assert\Type(
            type: 'string',
            message: 'Tytuł musi być ciągiem znaków.',
        )]
        public ?string $description,
        #[Assert\Type(
            type: 'bool',
            message: 'Status musi być wartością logiczną.',
        )]
        public ?bool $status,
        #[Assert\Type(
            type: PriorityEnum::class,
            message: 'Priorytet musi być jedną z dozwolonych wartości. (1-3)',
        )]
        public PriorityEnum $priority,
    ) {
    }
}
