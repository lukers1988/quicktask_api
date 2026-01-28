<?php

namespace App\Task\Domain\Entity;

use App\Task\Domain\Enum\PriorityEnum;
use App\Task\Domain\Model\TaskModel;
use App\Task\Infrastructure\Repository\TaskRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id = 0;

    #[ORM\Column(length: 30)]
    private string $title;

    #[ORM\Column(type: Types::TEXT)]
    private string $description;

    #[ORM\Column(enumType: PriorityEnum::class)]
    private PriorityEnum $priority = PriorityEnum::LOW;

    #[ORM\Column()]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column]
    private bool $status = false;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPriority(): ?PriorityEnum
    {
        return $this->priority;
    }

    public function setPriority(PriorityEnum $priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function isStatus(): bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function toTaskModel(): TaskModel
    {
        return new TaskModel(
            id: $this->getId(),
            title: $this->getTitle(),
            description: $this->getDescription(),
            status: $this->isStatus(),
            priority: $this->getPriority(),
            createdAt: $this->getCreatedAt(),
        );
    }
}
