<?php

namespace App\Tests\Unit\Fixtures;

use App\DataFixtures\AppFixtures;
use App\Entity\Task;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\ToolsException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class AppFixturesTest extends KernelTestCase
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var PasswordHasherFactoryInterface|mixed|object|Container|null
     */
    private PasswordHasherFactoryInterface $passwordHasherFactory;

    /**
     * Set up the test environment
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

        $this->entityManager = self::getContainer()->get('doctrine')->getManager();
        $this->passwordHasherFactory = self::getContainer()->get(PasswordHasherFactoryInterface::class);

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
     * Test the loading of fixtures
     *
     * @throws Exception
     */
    public function testLoadFixtures(): void
    {
            // Charger les fixtures
            $fixture = new AppFixtures($this->passwordHasherFactory);
            try {
                $fixture->load($this->entityManager);
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }

            // Assertions
            $users = $this->entityManager->getRepository(User::class)->findAll();
            $this->assertCount(4, $users, 'Four users should have been created.');

            $tasks = $this->entityManager->getRepository(Task::class)->findAll();
            $this->assertCount(16, $tasks, 'Sixteen tasks should have been created.');
    }

    /**
     * Tear down the test environment
     *
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
    }
}