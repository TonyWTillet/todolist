<?php

namespace App\Tests\Unit\Repository;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\ToolsException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var UserRepository|EntityRepository
     */
    private UserRepository|EntityRepository $userRepository;

    /**
     *  Set up the test environment
     *
     * @return void
     * @throws ToolsException
     */
    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->userRepository = $this->entityManager->getRepository(User::class);

        $this->purgeDatabase();
    }

    /**
     *  Purge the database
     *
     * @return void
     * @throws \Doctrine\ORM\Tools\ToolsException
     */
    private function purgeDatabase(): void
    {
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();

        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);
    }

    /**
     *  Test saving a user
     *
     * @return void
     */
    public function testSaveUser(): void
    {
        $user = new User();
        $user->setUsername('testuser');
        $user->setEmail('testuser@example.com');
        $user->setPassword('password123'); // Assume password is hashed elsewhere
        $user->setRoles(['ROLE_USER']);

        $this->userRepository->save($user, true);

        $savedUser = $this->userRepository->find($user->getId());
        $this->assertInstanceOf(User::class, $savedUser);
        $this->assertEquals('testuser', $savedUser->getUsername());
    }

    /**
     *  Test removing a user
     *
     * @return void
     */
    public function testRemoveUser(): void
    {
        $user = new User();
        $user->setUsername('usertoremove');
        $user->setEmail('usertoremove@example.com');
        $user->setPassword('password123');
        $user->setRoles(['ROLE_USER']);

        $this->userRepository->save($user, true);
        $userId = $user->getId();
        $this->userRepository->remove($user, true);

        $deletedUser = $this->userRepository->find($userId);

        $this->assertNull($deletedUser);
    }

    /**
     *  Test finding a user
     *
     * @return void
     */
    public function testFind(): void
    {
        $user = new User();
        $user->setUsername('finduser');
        $user->setEmail('finduser@example.com');
        $user->setPassword('password123');
        $user->setRoles(['ROLE_USER']);

        $this->userRepository->save($user, true);

        $foundUser = $this->userRepository->find($user->getId());
        $this->assertInstanceOf(User::class, $foundUser);
        $this->assertEquals('finduser', $foundUser->getUsername());
    }

    /**
     *  Test finding a user by criteria
     *
     * @return void
     */
    public function testFindOneBy(): void
    {
        $user = new User();
        $user->setUsername('uniqueuser');
        $user->setEmail('uniqueuser@example.com');
        $user->setPassword('password123');
        $user->setRoles(['ROLE_USER']);

        $this->userRepository->save($user, true);

        $foundUser = $this->userRepository->findOneBy(['username' => 'uniqueuser']);
        $this->assertInstanceOf(User::class, $foundUser);
        $this->assertEquals('uniqueuser', $foundUser->getUsername());
    }

    /**
     *  Test finding all users
     *
     * @return void
     */
    public function testFindAll(): void
    {
        $user1 = new User();
        $user1->setUsername('user1');
        $user1->setEmail('user1@example.com');
        $user1->setPassword('password123');
        $user1->setRoles(['ROLE_USER']);

        $user2 = new User();
        $user2->setUsername('user2');
        $user2->setEmail('user2@example.com');
        $user2->setPassword('password123');
        $user2->setRoles(['ROLE_USER']);

        $this->userRepository->save($user1, true);
        $this->userRepository->save($user2, true);

        $users = $this->userRepository->findAll();
        $this->assertCount(2, $users);
        $this->assertEquals('user1', $users[0]->getUsername());
        $this->assertEquals('user2', $users[1]->getUsername());
    }

    /**
     *  Test finding users by criteria
     *
     * @return void
     */
    public function testFindBy(): void
    {
        $user = new User();
        $user->setUsername('findbyuser');
        $user->setEmail('findbyuser@example.com');
        $user->setPassword('password123');
        $user->setRoles(['ROLE_USER']);

        $this->userRepository->save($user, true);

        $users = $this->userRepository->findBy(['username' => 'findbyuser']);
        $this->assertCount(1, $users);
        $this->assertEquals('findbyuser', $users[0]->getUsername());
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