<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class UserTest extends TestCase
{

    private PasswordHasherFactoryInterface $passwordHasherFactory;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        $this->passwordHasherFactory = $this->createMock(PasswordHasherFactoryInterface::class);
    }
    public function testUser(): void
    {
        $user = new User();
        $user->setUsername('User');
        $user->setEmail('user@gmail.com');
        $user->setPassword($this->passwordHasherFactory->getPasswordHasher(User::class)->hash('user'));
        $user->setRoles(['ROLE_USER']);

        $this->assertEquals('User', $user->getUsername());
        $this->assertEquals('user@gmail.com', $user->getEmail());
        $this->assertIsString($user->getPassword());
        $this->assertContains('ROLE_USER', $user->getRoles());
    }
}