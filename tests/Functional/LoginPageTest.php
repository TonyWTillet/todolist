<?php

namespace App\Tests\Functional;

use App\DataFixtures\AppFixtures;
use App\Tests\LogUtils;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class LoginPageTest extends WebTestCase
{
    /**
     * @var \Symfony\Bundle\FrameworkBundle\KernelBrowser
     */
    private $client;
    /**
     * @var LogUtils
     */
    private $logUtils;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->logUtils = new LogUtils($this->client);
    }

    /**
     *  Test login page
     *
     * @return void
     */
    public function testLoginPage(): void
    {
        $this->client->request('GET', '/login');
        $this->assertResponseIsSuccessful();
    }

    /**
     * Test submit correct authentication
     *
     * @return void
     */
    public function testSubmitCorrectAuthentication()
    {
        $crawler = $this->client->request('GET', '/login');
        $loginForm = $crawler->selectButton("Se connecter")->form();

        $this->assertNotEquals(null, $loginForm);

        $loginForm['_username'] = 'Admin';
        $loginForm['_password'] = 'admin';

        $crawler = $this->client->submit($loginForm);
        $crawler = $this->client->followRedirect();

        $this->assertStringContainsString('Créer une nouvelle tâche', $crawler->text());
    }

    /**
     * Test submit wrong authentication
     *
     * @return void
     */
    public function testSubmitWrongAuthentication()
    {
        $crawler = $this->client->request('GET', '/login');
        $loginForm = $crawler->selectButton("Se connecter")->form();

        $this->assertNotEquals(null, $loginForm);

        $loginForm['_username'] = 'xxx';
        $loginForm['_password'] = 'xxx';

        $crawler = $this->client->submit($loginForm);
        $crawler = $this->client->followRedirect();

        $this->assertStringContainsString('Invalid credentials.', $crawler->text());
    }
}