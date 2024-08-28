<?php

namespace App\Tests\Functional;

use App\DataFixtures\AppFixtures;
use App\Entity\Task;
use App\Tests\LogUtils;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class TaskPageTest extends WebTestCase
{
    /**
     * @var \Symfony\Bundle\FrameworkBundle\KernelBrowser
     */
    private $client;
    /**
     * @var LogUtils
     */
    private $logUtils;
    private $entityManager;


    /**
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
     * Test access add task button and check uri
     *
     * @return void
     */
    public function testAccessAddTaskButton(): void
    {
        $this->logUtils->login('admin');
        $crawler = $this->client->request('GET', '/tasks');
        $linkAddTask = $crawler->selectLink("Créer une tâche")->link()->getUri();
        $crawler = $this->client->request('GET', $linkAddTask);
        $this->assertResponseIsSuccessful();
    }

    /**
     * Test access edit task button and check uri
     *
     * @return void
     */
    public function testAccessEditTaskButton(): void
    {
        $this->logUtils->login('admin');
        $crawler = $this->client->request('GET', '/tasks');
        $linkEditTask = $crawler->selectLink("Modifier")->link()->getUri();
        $crawler = $this->client->request('GET', $linkEditTask);
        $this->assertResponseIsSuccessful();
    }

    /**
     * Test toggle task button
     *
     * @return void
     */
    public function testToggleTaskButton(): void
    {
        $this->logUtils->loginByUsername("Admin");
        $crawler = $this->client->request('GET', '/tasks');

        $task = $this->entityManager
            ->getRepository(Task::class)
            ->findOneBy([], ['id' => 'DESC']);

        $taskId = $task->getId();
        $isDoneBefore = $task->isDone();

        $formToggle = $crawler->selectButton('toggleTask'.$taskId)->form();
        $this->client->submit($formToggle);

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();

        $taskAfter = $this->entityManager
            ->getRepository(Task::class)
            ->findOneBy([], ['id' => 'DESC']);

        $isDoneAfter= $taskAfter->isDone();

        $this->assertNotEquals($isDoneBefore, $isDoneAfter);
    }

    /**
     * Test delete task button
     *
     * @return void
     */
    public function testDeleteTaskButton(): void
    {
        $this->logUtils->loginByUsername("Admin");
        $crawler = $this->client->request('GET', '/tasks');

        $task = $this->entityManager
            ->getRepository(Task::class)
            ->findOneBy([], ['id' => 'DESC']);

        $taskId = $task->getId();

        $formDelete = $crawler->selectButton('deleteTask'.$taskId)->form();
        $this->client->submit($formDelete);

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();

        $taskAfter = $this->entityManager
            ->getRepository(Task::class)
            ->findOneBy(['id' => $taskId]);

        $this->assertNull($taskAfter);
    }

    /**
     * Test redirect after add task
     *
     * @return void
     */
    public function testRedirectAfterAddTask(): void
    {
        $this->logUtils->login('admin');
        $crawler = $this->client->request('GET', '/tasks/create');
        $form = $crawler->selectButton('Ajouter')->form();
        $form['task[title]'] = 'Test';
        $form['task[content]'] = 'Test';
        $this->client->submit($form);
        $this->assertResponseRedirects('/tasks');
    }

    /**
     * Test redirect after edit task
     *
     * @return void
     */
    public function testRedirectAfterEditTask(): void
    {
        $this->logUtils->login('admin');
        $crawler = $this->client->request('GET', '/tasks');
        $linkEditTask = $crawler->selectLink("Modifier")->link()->getUri();
        $crawler = $this->client->request('GET', $linkEditTask);
        $form = $crawler->selectButton('Modifier')->form();
        $form['task[title]'] = 'Test';
        $form['task[content]'] = 'Test';
        $this->client->submit($form);
        $this->assertResponseRedirects('/tasks');
    }

    /**
     * Test go back to task list button
     *
     * @return void
     */
    public function testGoBackToTaskListButton(): void
    {
        $this->logUtils->login('admin');
        $crawler = $this->client->request('GET', '/tasks/create');
        $linkBack = $crawler->selectLink("Retour à la liste des tâches")->link()->getUri();
        $crawler = $this->client->request('GET', $linkBack);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Liste des tâches');
    }


}
