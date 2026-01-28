<?php

declare(strict_types=1);

namespace App\Tests\Task\Factory;

use App\Task\Domain\Entity\Task;
use App\Task\Domain\Enum\PriorityEnum;
use App\Task\Domain\Message\CreateTask;
use App\Task\Infrastructure\Repository\TaskRepository;
use Zenstruck\Foundry\LazyValue;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

use function Zenstruck\Foundry\lazy;

/**
 * @extends PersistentProxyObjectFactory<Task>
 */
final class TaskFactory extends PersistentProxyObjectFactory
{
    public function __construct(
        private readonly TaskRepository $taskRepository,
    ) {
        parent::__construct();
    }

    public function createSimpleTask(): Task
    {
        $createTask = new CreateTask(
            title: 'Simple Task',
            description: 'This is a simple task description.',
            status: false,
            priority: PriorityEnum::LOW
        );

        return $this->taskRepository->save($createTask);
    }

    /**
     * @return array<string, LazyValue>
     */
    protected function defaults(): array
    {
        return [
            'title' => lazy(fn () => self::faker()->sentence(3)),
            'description' => lazy(fn () => self::faker()->paragraph()),
            'status' => lazy(fn () => self::faker()->boolean()),
            'priority' => lazy(fn () => self::faker()->numberBetween(1, 3)),
        ];
    }

    public static function class(): string
    {
        return Task::class;
    }

    protected function getRepositoryClass(): string
    {
        return TaskRepository::class;
    }

    protected function initialize(): static
    {
        return $this
            ->withoutPersisting()
        ;
    }
}
