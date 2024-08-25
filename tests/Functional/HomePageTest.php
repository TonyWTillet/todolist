<?php

namespace App\Tests\Functional;

use App\Controller\SecurityController;
use App\DataFixtures\AppFixtures;
use App\Tests\LogUtils;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class HomePageTest extends WebTestCase
{
    private $client;
    private $logUtils;
    private $entityManager;
    private $passwordHasherFactory;
    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->logUtils = new LogUtils($this->client);

        $container = self::getContainer();

        $passwordHasherFactory = $container->get(PasswordHasherFactoryInterface::class);

        $this->entityManager = $container->get('doctrine')->getManager();

        $this->passwordHasherFactory = self::getContainer()->get(PasswordHasherFactoryInterface::class);

        $purger = new ORMPurger($this->entityManager);

        $loader = new Loader();
        $loader->addFixture(new AppFixtures($passwordHasherFactory));

        $executor = new ORMExecutor($this->entityManager, $purger);
        $executor->execute($loader->getFixtures());
    }

    public function testHomePage(): void
    {

        $this->client->request('GET', '/');
        $this->assertResponseIsSuccessful();
    }

    /**
     * Test access add task button and check uri
     *
     * @return void
     */
    public function testAccessAddTaskButton()
    {
        $this->logUtils->login('admin');
        $crawler = $this->client->request('GET', '/');
        $linkAddTask = $crawler->selectLink("Créer une nouvelle tâche")->link()->getUri();
        $crawler = $this->client->request('GET', $linkAddTask);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $titlePage = $crawler->filter('h1')->text();
        $this->assertStringContainsString("Créer une tâche", $titlePage);
    }

    /**
     * Test access all tasks button
     *
     * @return void
     */
    public function testAccessAllTasksButton()
    {
        $this->logUtils->login('admin');
        $crawler = $this->client->request('GET', '/');
        $linkViewTasks = $crawler->selectLink("Consulter la liste des tâches")->link()->getUri();

        $crawler = $this->client->request('GET', $linkViewTasks);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $titlePage = $crawler->filter('h1')->text();
        $this->assertStringContainsString("Liste des tâches", $titlePage);
    }

    /**
     * Test access create user when admin is connected
     *
     * @return void
     */
    public function testAccessCreateUserButton(): void
    {
        $this->logUtils->login('admin');
        $crawler = $this->client->request('GET', '/');
        $linkCreateUser = $crawler->selectLink("Créer un utilisateur")->link()->getUri();

        $crawler = $this->client->request('GET', $linkCreateUser);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $titlePage = $crawler->filter('h1')->text();
        $this->assertStringContainsString("Créer un utilisateur", $titlePage);
    }



}