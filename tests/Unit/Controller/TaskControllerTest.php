<?php

namespace App\Tests\Unit\Controller;

use AllowDynamicProperties;
use App\DataFixtures\AppFixtures;
use App\Entity\Task;
use App\Entity\User;
use App\Tests\LogUtils;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\ToolsException;
use Random\RandomException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

#[AllowDynamicProperties] class TaskControllerTest extends WebTestCase
{
    /**
     * @var KernelBrowser
     */
    private KernelBrowser $client;
    /**
     * @var LogUtils
     */
    private LogUtils $logUtils;
    /**
     * @var
     */
    private int $idCreatedTask;
    /**
     * @var
     */
    private $entityManager;

    /**
     *  Set up the test environment
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->logUtils = new LogUtils($this->client);

        $container = self::getContainer();

        $passwordHasherFactory = $container->get(PasswordHasherFactoryInterface::class);

        $this->entityManager = $container->get('doctrine')->getManager();

        $purger = new ORMPurger($this->entityManager);

        $loader = new Loader();
        $loader->addFixture(new AppFixtures($passwordHasherFactory));

        $executor = new ORMExecutor($this->entityManager, $purger);
        $executor->execute($loader->getFixtures());
    }
    

    /**
     * Test access tasks list
     *
     * @return void
     */
    public function testAccessTaskList()
    {
        $this->logUtils->login('user');
        $this->client->request('GET', "/tasks");
        $this->assertResponseStatusCodeSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Test access tasks list as admin
     *
     * @return void
     */
    public function testAccessTaskListAsAdmin(): void
    {
        $this->logUtils->login('admin');
        $this->client->request('GET', "/tasks");
        $this->assertResponseStatusCodeSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Test create task
     *
     * @return void
     */
    public function testCreateTask()
    {
        $author = 'User';
        $title = 'Test title / add task';
        $content = 'Test content / add task';

        $this->logUtils->loginByUsername($author);
        $crawler = $this->client->request('GET', "/tasks/create");
        $crsf = $crawler->filter('input[name="task[_token]"]')->extract(array('value'))[0];

        $this->client->request('POST', "/tasks/create", [
            'task' => [
                'title' => $title,
                'content' => $content,
                '_token' => $crsf
            ]
        ]);

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $taskCreated = $this->entityManager->getRepository(Task::class)->findOneBy(['title' => $title]);

        $this->assertEquals($title, $taskCreated->getTitle());
        $this->assertEquals($author, $taskCreated->getUser()->getUsername());
        $this->assertEquals($content, $taskCreated->getContent());
    }

    /**
     * Test edit task
     *
     * @return void
     * @throws ToolsException
     */
    public function testEditTask(): void
    {
        $author = 'User';
        $this->logUtils->loginByUsername($author);
        $authorId = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $author])->getId();
        $task = $this->entityManager->getRepository(Task::class)->findOneBy(['user' => $authorId]);

        // Edit task
        $editTitle = 'Test title / edit task';
        $editContent = 'Test content / edit task';

        $crawler = $this->client->request('GET', "/tasks/{$task->getId()}/edit");
        $crsf = $crawler->filter('input[name="task[_token]"]')->extract(array('value'))[0];

        $this->client->request('POST', "/tasks/{$task->getId()}/edit", [
            'task' => [
                'title' => $editTitle,
                'content' => $editContent,
                '_token' => $crsf
            ]
        ]);

        $taskEdited = $this->entityManager->getRepository(Task::class)->findOneBy(['title' => $editTitle]);

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertNotEquals($task->getTitle(), $taskEdited->getTitle());
        $this->assertEquals($task->getUser()->getUsername(), $taskEdited->getUser()->getUsername());
        $this->assertNotEquals($task->getContent(), $taskEdited->getContent());


    }

    /**
     * Test remove task by unauthorized
     *
     * @return void
     */
    public function testEditTaskByUnauthorized()
    {
        $author = 'user';
        $this->logUtils->login($author);
        $authorId = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $author])->getId();
        $taskUserValid = $this->entityManager->getRepository(Task::class)->findOneBy(['user' => $authorId]);
        $idCreatedTask = $taskUserValid->getId();

        $urlEditTask = "/tasks/" . $idCreatedTask . "/edit";

        $this->logUtils->loginByUsername("user2");
        $this->client->request('POST', $urlEditTask);
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();
        $errorMessage = $crawler->filter('div.alert.alert-danger')->text();
        $this->assertStringContainsString('Vous ne pouvez pas modifier cette tâche', $errorMessage);

        $task = $this->entityManager
            ->getRepository(Task::class)
            ->findOneBy(['id' => $idCreatedTask]);

        $this->assertEquals($idCreatedTask, $task->getId());
    }

    /**
     * Test toggle task
     *
     * @return void
     */
    public function testToggleTask()
    {
        $author = 'User';
        $this->logUtils->loginByUsername($author);
        $authorId = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $author])->getId();
        $task =$this->entityManager->getRepository(Task::class)->findOneBy(['user' => $authorId]);

        $urlToggle = '/tasks/' . $task->getId() . '/toggle';
        $taskIsDoneBefore = $task->isDone();

        $this->client->request('POST', $urlToggle);
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $taskIsDoneAfter = $task->isDone();

        $this->assertNotEquals($taskIsDoneBefore, $taskIsDoneAfter);
    }

    /**
     * Test remove task by unauthorized
     *
     * @return void
     */
    public function testToggleTaskByUnauthorized()
    {
        $author = 'user';
        $this->logUtils->login($author);
        $authorId = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $author])->getId();
        $taskUserValid = $this->entityManager->getRepository(Task::class)->findOneBy(['user' => $authorId]);
        $idCreatedTask = $taskUserValid->getId();

        $urlToggleTask = "/tasks/" . $idCreatedTask . "/toggle";

        $this->logUtils->loginByUsername("user2");
        $this->client->request('POST', $urlToggleTask);
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();
        $errorMessage = $crawler->filter('div.alert.alert-danger')->text();
        $this->assertStringContainsString('Vous ne pouvez pas modifier cette tâche', $errorMessage);

        $task = $this->entityManager
            ->getRepository(Task::class)
            ->findOneBy(['id' => $idCreatedTask]);

        $this->assertEquals($taskUserValid->isDone(), $task->isDone());
    }

    /**
     * Test remove task by unauthorized
     *
     * @return void
     */
    public function testRemoveTaskByUnauthorized()
    {
        $author = 'user';
        $this->logUtils->login($author);
        $authorId = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $author])->getId();
        $taskUserValid = $this->entityManager->getRepository(Task::class)->findOneBy(['user' => $authorId]);
        $idCreatedTask = $taskUserValid->getId();

        $urlDeleteTask = "/tasks/" . $idCreatedTask . "/delete";

        $this->logUtils->loginByUsername("user2");
        $this->client->request('POST', $urlDeleteTask);
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();
        $errorMessage = $crawler->filter('div.alert.alert-danger')->text();
        $this->assertStringContainsString('Vous ne pouvez pas supprimer cette tâche', $errorMessage);

        $task = $this->entityManager
            ->getRepository(Task::class)
            ->findOneBy(['id' => $idCreatedTask]);

        $this->assertEquals($idCreatedTask, $task->getId());
    }

    /**
     * Test remove task by authorized
     *
     * @return void
     */
    public function testRemoveTaskByAuthorized()
    {
        $author = 'User';
        $this->logUtils->login($author);
        $authorId = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $author])->getId();
        $task = $this->entityManager->getRepository(Task::class)->findOneBy(['user' => $authorId]);
        $idCreatedTask = $task->getId();

        $urlDeleteTask = "/tasks/" . $idCreatedTask . "/delete";

        $this->logUtils->loginByUsername("User");

        $this->client->request('POST', $urlDeleteTask);

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();
        $this->assertStringContainsString('La tâche a bien été supprimée', $crawler->text());

        $task = $this->entityManager
            ->getRepository(Task::class)
            ->findOneBy(['id' => $idCreatedTask]);

        $this->assertEquals(null, $task);
    }

    /**
     * Test remove anonymous task by admin
     *
     * @return void
     * @throws RandomException
     */
    public function testRemoveAnonymousTaskByAdmin()
    {
        $anonymousUser = $this->entityManager->getRepository(User::class)->findOneBy(['username' => 'Anonyme'])->getId();

        $anonymousTasks = $this->entityManager
            ->getRepository(Task::class)
            ->findBy(['user' => $anonymousUser]);

        $targetTask = $anonymousTasks[random_int(0, count($anonymousTasks) - 1)];
        $idTargetTask = $targetTask->getId();

        $this->logUtils->login("admin");
        $urlDeleteTargetTask = "/tasks/" . $idTargetTask . "/delete";

        $crawler = $this->client->request('POST', $urlDeleteTargetTask);

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();
        $this->assertStringContainsString('La tâche a bien été supprimée', $crawler->text());

        $task = $this->entityManager
            ->getRepository(Task::class)
            ->findOneBy(['id' => $idTargetTask]);

        $this->assertEquals(null, $task);
    }

    /**
     * Get the value of idCreatedTask
     *
     * @return int
     */
    public function getIdCreatedTask(): int
    {
        return $this->idCreatedTask;
    }

    /**
     * Set the value of idCreatedTask
     *
     * @param $idCreatedTask
     * @return  self
     */
    public function setIdCreatedTask($idCreatedTask): static
    {
        $this->idCreatedTask = $idCreatedTask;

        return $this;
    }

}
