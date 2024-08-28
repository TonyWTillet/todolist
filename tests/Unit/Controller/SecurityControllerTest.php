<?php

namespace App\Tests\Unit\Controller;

use App\Controller\SecurityController;
use App\DataFixtures\AppFixtures;
use App\Tests\LogUtils;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\CoversNothing;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
#[CoversClass(SecurityController::class)]
class SecurityControllerTest extends WebTestCase
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

        $this->securityController = new SecurityController();
    }

    /**
     *  Test the list action
     *
     * @return void
     */
    public function testLoginAction(): void
    {
        $this->client->request('GET', "/login");
        $this->assertResponseStatusCodeSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    /**
     *  Test redirect if user is logged in
     *
     * @return void
     */
    public function testLoginActionRedirectIfLoggedIn(): void
    {
        $this->logUtils->login('admin');
        $this->client->request('GET', "/login");
        $this->assertResponseRedirects();
    }

    /**
     * Test logout
     *
     * @return void
     */
    public function testLogout()
    {
        $this->logUtils->login('admin');
        $this->client->request('GET', "/logout");
        $this->client->request('GET', "/tasks");
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }
}
