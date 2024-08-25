<?php

namespace App\Tests\Unit\Repository;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\ToolsException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskRepositoryTest extends KernelTestCase
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var EntityRepository|TaskRepository
     */
    private TaskRepository|EntityRepository $taskRepository;

    /**
     * @return void
     * @throws ToolsException
     */
    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->taskRepository = $this->entityManager->getRepository(Task::class);

        // Purge the database
        $this->purgeDatabase();
    }

    /**
     * Purge the database
     *
     * @return void
     * @throws ToolsException
     */
    private function purgeDatabase(): void
    {
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();

        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);
    }

    /**
     *  Save a task
     *
     * @return void
     */
    public function testSaveTask(): void
    {
        $task = new Task();
        $task->setTitle('Test Task');
        $task->setContent('This is a test task.');

        $this->taskRepository->save($task, true);

        $savedTask = $this->taskRepository->find($task->getId());
        $this->assertInstanceOf(Task::class, $savedTask);
        $this->assertEquals('Test Task', $savedTask->getTitle());
    }

    /**
     *  Remove a task
     *
     * @return void
     */
    public function testRemoveTask(): void
    {
        $task = new Task();
        $task->setTitle('Task to Remove');
        $task->setContent('This task will be removed.');

        $this->taskRepository->save($task, true);
        $taskId = $task->getId();
        $this->taskRepository->remove($task, true);

        $deletedTask = $this->taskRepository->find($taskId);

        $this->assertNull($deletedTask);
    }

    /**
     *  Find a task
     *
     * @return void
     */
    public function testFind(): void
    {
        $task = new Task();
        $task->setTitle('Task to Find');
        $task->setContent('This task is used for testing find.');

        $this->taskRepository->save($task, true);

        $foundTask = $this->taskRepository->find($task->getId());
        $this->assertInstanceOf(Task::class, $foundTask);
        $this->assertEquals('Task to Find', $foundTask->getTitle());
    }

    /**
     *  Find a task by criteria
     *
     * @return void
     */
    public function testFindOneBy(): void
    {
        $task = new Task();
        $task->setTitle('Unique Task');
        $task->setContent('This task is unique.');

        $this->taskRepository->save($task, true);

        $foundTask = $this->taskRepository->findOneBy(['title' => 'Unique Task']);
        $this->assertInstanceOf(Task::class, $foundTask);
        $this->assertEquals('Unique Task', $foundTask->getTitle());
    }

    /**
     *  Find all tasks
     *
     * @return void
     */
    public function testFindAll(): void
    {
        $task1 = new Task();
        $task1->setTitle('Task 1');
        $task1->setContent('First task.');

        $task2 = new Task();
        $task2->setTitle('Task 2');
        $task2->setContent('Second task.');

        $this->taskRepository->save($task1, true);
        $this->taskRepository->save($task2, true);

        $tasks = $this->taskRepository->findAll();
        $this->assertCount(2, $tasks);
        $this->assertEquals('Task 1', $tasks[0]->getTitle());
        $this->assertEquals('Task 2', $tasks[1]->getTitle());
    }

    /**
     *  Find tasks by criteria
     *
     * @return void
     */
    public function testFindBy(): void
    {
        $task = new Task();
        $task->setTitle('Task to Find By');
        $task->setContent('This task is used for testing findBy.');

        $this->taskRepository->save($task, true);

        $tasks = $this->taskRepository->findBy(['title' => 'Task to Find By']);
        $this->assertCount(1, $tasks);
        $this->assertEquals('Task to Find By', $tasks[0]->getTitle());
    }

    /**
     *  Tear down the test environment
     *
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
    }
}