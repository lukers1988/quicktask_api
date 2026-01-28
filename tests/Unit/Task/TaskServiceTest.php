<?php
declare(strict_types=1);

namespace Unit\Task;

use App\Task\Application\Service\TaskService;
use App\Task\Domain\Entity\Task;
use App\Task\Domain\Message\ChangeTaskStatus;
use App\Task\Domain\Message\CreateTask;
use App\Task\Domain\Model\TaskModel;
use App\Task\Infrastructure\Repository\TaskRepository;
use Codeception\Test\Unit;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class TaskServiceTest extends Unit
{
    public function testCollection(): void
    {
        $task = $this->createMock(Task::class);
        $taskModel = $this->createMock(TaskModel::class);

        $task
            ->expects($this->once())
            ->method('toTaskModel')
            ->willReturn($taskModel);

        $repository = $this->createMock(TaskRepository::class);
        $repository
            ->method('collection')
            ->willReturn([$task]);

        $service = new TaskService(
            $repository,
            $this->createMock(MessageBusInterface::class)
        );

        $result = $service->getTasks();

        $this->assertSame([$taskModel], $result);
    }

    public function testCreateTaskReturnsTaskModelWhenHandled(): void
    {
        $command = $this->createMock(CreateTask::class);
        $taskModel = $this->createMock(TaskModel::class);

        $bus = $this->createMock(MessageBusInterface::class);
        $bus
            ->expects($this->once())
            ->method('dispatch')
            ->with($command)
            ->willReturn($this->envelopeWithResult($taskModel));

        $service = new TaskService(
            $this->createMock(TaskRepository::class),
            $bus
        );

        $result = $service->createTask($command);

        $this->assertSame($taskModel, $result);
    }

    public function testCreateTaskReturnsNullWhenNotHandled(): void
    {
        $command = $this->createMock(CreateTask::class);

        $bus = $this->createMock(MessageBusInterface::class);
        $bus
            ->method('dispatch')
            ->willReturn(new Envelope($command));

        $service = new TaskService(
            $this->createMock(TaskRepository::class),
            $bus
        );

        $this->assertNull($service->createTask($command));
    }

    public function testChangeTaskStatusReturnsModel(): void
    {
        $command = $this->createMock(ChangeTaskStatus::class);
        $taskModel = $this->createMock(TaskModel::class);

        $bus = $this->createMock(MessageBusInterface::class);
        $bus
            ->method('dispatch')
            ->willReturn($this->envelopeWithResult($taskModel));

        $service = new TaskService(
            $this->createMock(TaskRepository::class),
            $bus
        );

        $this->assertSame(
            $taskModel,
            $service->changeTaskStatus($command)
        );
    }

    private function envelopeWithResult(mixed $result): Envelope
    {
        return new Envelope(
            new \stdClass(),
            [new HandledStamp($result, 'handler')]
        );
    }
}