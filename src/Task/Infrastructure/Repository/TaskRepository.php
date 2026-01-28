<?php

declare(strict_types=1);

namespace App\Task\Infrastructure\Repository;

use App\Task\Domain\Entity\Task;
use App\Task\Domain\Message\ChangeTaskStatus;
use App\Task\Domain\Message\CreateTask;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Task>
 */
class TaskRepository extends ServiceEntityRepository implements TaskRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function collection(): array
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.priority', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function save(CreateTask $createTask): Task
    {
        $task = new Task()
            ->setTitle($createTask->title)
            ->setDescription($createTask->description)
            ->setPriority($createTask->priority)
            ->setStatus($createTask->status)
            ->setCreatedAt(new \DateTimeImmutable())
        ;

        $this->getEntityManager()->persist($task);
        $this->getEntityManager()->flush();

        return $task;
    }

    public function update(ChangeTaskStatus $changeTaskStatus): Task
    {
        $task = $this->find($changeTaskStatus->id);

        $task->setStatus($changeTaskStatus->status);
        $this->getEntityManager()->flush();

        return $task;
    }
}
