<?php

namespace App\Tests\Unit\Controller;

use AllowDynamicProperties;
use App\DataFixtures\AppFixtures;
use App\Entity\User;
use App\Tests\LogUtils;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
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

        $this->passwordHasherFactory = self::getContainer()->get(PasswordHasherFactoryInterface::class);

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

    /**
     *  Test the creation action
     *
     * @return void
     */
    public function testCreateAction(): void
    {
        $username = 'usertestcreate';
        $password = 'password';
        $email = 'usertestcreate@gmail.com';
        $roles = ['ROLE_USER', 'ROLE_ADMIN'];

        $this->logUtils->loginByUsername('Admin');

        $crawler = $this->client->request('GET', "/users/create");

        $csrfToken = $crawler->filter('input[name="user[_token]"]')->attr('value');

        $this->client->request('POST', "/users/create", [
            'user' => [
                'username' => $username,
                'password' => [
                    'first' => $password,
                    'second' => $password,
                ],
                'roles' => $roles,
                'email' => $email,
                '_token' => $csrfToken,
            ]
        ]);

        $crawler = $this->client->followRedirect();

        $successMessage = $crawler->filter('div.alert.alert-success')->text();
        $this->assertStringContainsString('L\'utilisateur a bien été ajouté.', $successMessage);

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $username]);
        $this->assertNotNull($user);
    }

    /**
     *  Test the edit action
     *
     * @return void
     */
    public function testEditAction(): void
    {
        $username = 'usertestedit';
        $password = 'password';
        $email = 'usertestedit@gmail.com';
        $roles = ['ROLE_USER', 'ROLE_ADMIN'];

        $userId = $this->entityManager->getRepository(User::class)->findOneBy(['username' => 'User'])->getId();

        $this->logUtils->loginByUsername('Admin');

        $crawler = $this->client->request('GET', "/users/". $userId ."/edit");

        $csrfToken = $crawler->filter('input[name="user[_token]"]')->attr('value');

        $this->client->request('POST', "/users/". $userId ."/edit", [
            'user' => [
                'username' => $username,
                'password' => [
                    'first' => $password,
                    'second' => $password,
                ],
                'roles' => $roles,
                'email' => $email,
                '_token' => $csrfToken,
            ]
        ]);

        $crawler = $this->client->followRedirect();

        $successMessage = $crawler->filter('div.alert.alert-success')->text();
        $this->assertStringContainsString('L\'utilisateur a bien été modifié', $successMessage);

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $username]);
        $this->assertNotNull($user);

    }

}