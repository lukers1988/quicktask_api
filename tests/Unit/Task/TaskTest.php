<?php
declare(strict_types=1);

namespace Unit\Task;

use App\Task\Domain\Entity\Task;
use App\Task\Domain\Enum\PriorityEnum;
use Codeception\Test\Unit;

class TaskTest extends Unit
{
    public function testDefaults(): void
    {
        $task = new Task();

        $this->assertSame(PriorityEnum::LOW, $task->getPriority());
        $this->assertFalse($task->isStatus());
        $this->assertSame(0, $task->getId());
    }

    public function testSetAndGetTitle(): void
    {
        $task = new Task();

        $task->setTitle('Test title');

        $this->assertSame('Test title', $task->getTitle());
    }

    public function testSetAndGetDescription(): void
    {
        $task = new Task();

        $task->setDescription('Long description');

        $this->assertSame('Long description', $task->getDescription());
    }

    public function testSetAndGetPriority(): void
    {
        $task = new Task();

        $task->setPriority(PriorityEnum::HIGH);

        $this->assertSame(PriorityEnum::HIGH, $task->getPriority());
    }

    public function testSetAndGetCreatedAt(): void
    {
        $task = new Task();
        $date = new \DateTimeImmutable('2025-01-01 12:00:00');

        $task->setCreatedAt($date);

        $this->assertSame($date, $task->getCreatedAt());
    }

    public function testSetAndGetStatus(): void
    {
        $task = new Task();

        $task->setStatus(true);

        $this->assertTrue($task->isStatus());
    }

    public function testFluentInterface(): void
    {
        $date = new \DateTimeImmutable();

        $task = (new Task())
            ->setTitle('Fluent title')
            ->setDescription('Fluent description')
            ->setPriority(PriorityEnum::MEDIUM)
            ->setCreatedAt($date)
            ->setStatus(true);

        $this->assertSame('Fluent title', $task->getTitle());
        $this->assertSame('Fluent description', $task->getDescription());
        $this->assertSame(PriorityEnum::MEDIUM, $task->getPriority());
        $this->assertSame($date, $task->getCreatedAt());
        $this->assertTrue($task->isStatus());
    }
}