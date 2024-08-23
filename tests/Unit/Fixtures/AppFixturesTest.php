<?php

namespace App\Tests\Unit\Fixtures;

use App\DataFixtures\AppFixtures;
use App\Entity\Task;
use App\Entity\User;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class AppFixturesTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private PasswordHasherFactoryInterface $passwordHasherFactory;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->entityManager = self::getContainer()->get('doctrine')->getManager();
        $this->passwordHasherFactory = self::getContainer()->get(PasswordHasherFactoryInterface::class);
    }

    public function testLoadFixtures(): void
    {
        // Charger les fixtures
        $fixture = new AppFixtures($this->passwordHasherFactory);
        $fixture->load($this->entityManager);

        // Assertions
        $users = $this->entityManager->getRepository(User::class)->findAll();
        $this->assertCount(3, $users, 'Three users should have been created.');

        $tasks = $this->entityManager->getRepository(Task::class)->findAll();
        $this->assertCount(15, $tasks, 'Fifteen tasks should have been created.');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
    }
}