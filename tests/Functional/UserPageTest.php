<?php

namespace App\Tests\Functional;

use App\DataFixtures\AppFixtures;
use App\Tests\LogUtils;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class UserPageTest extends WebTestCase
{
    /**
     * @var KernelBrowser
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
     * Test access add user button and check uri
     *
     * @return void
     */
    public function testAccessAddUserButton(): void
    {
        $this->logUtils->login('admin');
        $crawler = $this->client->request('GET', '/users');
        $linkAddUser = $crawler->selectLink("Créer un utilisateur")->link()->getUri();
        $crawler = $this->client->request('GET', $linkAddUser);
        $this->assertResponseIsSuccessful();
    }

    /**
     * Test access edit user button and check uri
     *
     * @return void
     */
    public function testAccessEditUserButton(): void
    {
        $this->logUtils->login('admin');
        $crawler = $this->client->request('GET', '/users');
        $linkEditUser = $crawler->selectLink("Modifier")->link()->getUri();
        $crawler = $this->client->request('GET', $linkEditUser);
        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('Modifier', $crawler->filter('h1')->text());
    }

    public function testUserListButton(): void
    {
        $this->logUtils->login('admin');
        $crawler = $this->client->request('GET', '/');
        $link = $crawler->selectLink("Liste des utilisateurs")->link()->getUri();
        $crawler = $this->client->request('GET', $link);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Liste des utilisateurs');
    }
}