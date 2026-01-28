<?php

declare(strict_types=1);

namespace Api\Task;

use App\Task\Domain\Enum\PriorityEnum;
use App\Tests\Task\Factory\TaskFactory;
use Tests\Support\ApiTester;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class TaskCest
{
    use Factories;
    use ResetDatabase;

    private TaskFactory $taskFactory;

    public function _before(): void
    {
        $this->taskFactory = TaskFactory::new();
    }

    /**
     * @throws Exception
     */
    public function getEmptyList(ApiTester $I): void
    {
        $I->sendGet('/task');
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            'tasks' => [],
        ]);
    }

    public function getNonEmptyList(ApiTester $I): void
    {
        $task = $this->taskFactory->createSimpleTask();

        $I->sendGet('/task');
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            'tasks' => [[
                'id' => $task->getId(),
                'title' => $task->getTitle(),
                'description' => $task->getDescription(),
                'priority' => $task->getPriority()->value,
                'status' => false,
            ]],
        ]);
    }

    public function createTaskSuccessfully(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/task', [
            'title' => 'New Task',
            'description' => 'This is a new task description.',
            'status' => false,
            'priority' => PriorityEnum::MEDIUM->value,
        ]);

        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(201);
        $I->seeResponseContainsJson([
            'message' => 'Zadanie zostało utworzone pomyślnie.',
            'task' => [
                'title' => 'New Task',
                'description' => 'This is a new task description.',
                'status' => false,
                'priority' => PriorityEnum::MEDIUM->value,
            ],
        ]);
    }

    public function updateTaskStatusSuccessfully(ApiTester $I): void
    {
        $task = $this->taskFactory->createSimpleTask();

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPatch('/task/' . $task->getId() . '/change_status', [
            'status' => true,
        ]);

        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            'message' => 'Status zadania został zaktualizowany.',
            'task' => [
                'id' => $task->getId(),
                'title' => $task->getTitle(),
                'description' => $task->getDescription(),
                'priority' => $task->getPriority()->value,
                'status' => true,
            ],
        ]);
    }

    public function createTaskWithoutTitle(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/task', [
            'title' => '',
            'description' => 'This is a simple task description.',
            'status' => false,
            'priority' => PriorityEnum::LOW->value,
        ]);

        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(500);
        $I->seeResponseContainsJson([
            'error' => [
                [
                    'status' => 500,
                    'detail' => 'Błąd podczas przetwarzania żadania.',
                    'type' => 'UnprocessableEntityHttpException',
                ],
            ],
        ]);
    }

    public function createTaskWithTitleLongerThan30Chars(ApiTester $I): void
    {

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/task', [
            'title' => 'This title is definitely way longer than thirty characters.',
            'description' => 'This is a simple task description.',
            'status' => false,
            'priority' => PriorityEnum::LOW->value,
        ]);

        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(500);
        $I->seeResponseContainsJson([
            'error' => [
                [
                    'status' => 500,
                    'detail' => 'Błąd podczas przetwarzania żadania.',
                    'type' => 'UnprocessableEntityHttpException',
                ],
            ],
        ]);
    }

    public function createTaskWithPriorityOutOfRange(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/task', [
            'title' => 'New Task',
            'description' => 'This is a simple task description.',
            'status' => false,
            'priority' => 5,
        ]);

        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(500);
        $I->seeResponseContainsJson([
            'error' => [
                [
                    'status' => 500,
                    'detail' => 'Błąd podczas przetwarzania żadania.',
                    'type' => 'InvalidArgumentException',
                ],
            ],
        ]);
    }
}
