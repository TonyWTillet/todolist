<?php

namespace App\Tests\Unit\Controller;

use AllowDynamicProperties;
use App\DataFixtures\AppFixtures;
use App\Tests\LogUtils;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

#[AllowDynamicProperties] class UserControllerTest extends WebTestCase
{
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
     *  Test the list action
     *
     * @return void
     */
    public function testListAction(): void
    {
        $this->logUtils->login('admin');
        $this->client->request('GET', "/users");
        $this->assertResponseStatusCodeSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

}