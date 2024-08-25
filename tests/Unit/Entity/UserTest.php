<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

#[CoversClass(User::class)]
#[CoversClass(Task::class)]
class UserTest extends KernelTestCase
{
    private PasswordHasherFactoryInterface $passwordHasherFactory;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->passwordHasherFactory = $this->createMock(PasswordHasherFactoryInterface::class);
    }
    /**
     * Test id assign task
     *
     * @return void
     *
     */
    public function testId()
    {
        $user = new User();
        $id = null;

        $this->assertEquals($id, $user->getId());
    }

    /**
     * Test field username
     *
     * @return void
     */
    public function testUsername()
    {
        $user = new User();
        $username = "Test username";

        $user->setUsername($username);
        $this->assertEquals($username, $user->getUsername());
    }

    /**
     * Test field password
     *
     * @return void
     */
    public function testPassword()
    {
        $user = new User();
        $password = "password";

        $user->setPassword($password);
        $this->assertEquals($password, $user->getPassword());
    }

    /**
     * Test field email
     *
     * @return void
     */
    public function testEmail()
    {
        $user = new User();
        $email = "user@gmail.com";

        $user->setEmail($email);
        $this->assertEquals($email, $user->getEmail());
    }

    /**
     * Test add task
     *
     * @return void
     */
    public function testAddTask()
    {
        $user = new User();
        $task = new Task();

        $user->addTask($task);
        $this->assertEquals($task, $user->getTasks()[0]);
    }

    /**
     * Test remove task
     *
     * @return void
     */
    public function testRemoveTask()
    {
        $user = new User();
        $task = new Task();

        $user->addTask($task);
        $this->assertEquals($task, $user->getTasks()[0]);

        $user->removeTask($task);
        $this->assertEquals([], $user->getTasks()->toArray());
    }

    /**
     * Test field role
     *
     * @return void
     */
    public function testRoles()
    {
        $user = new User();
        $role = ["ROLE_USER"];

        $user->setRoles($role);
        $this->assertEquals($role, $user->getRoles());
    }

    /**
     * Test field user identifier
     *
     * @return void
     */
    public function testUserIdentifier()
    {
        $user = new User();
        $username = "Test username";

        $user->setUsername($username);
        $this->assertEquals($username, $user->getUserIdentifier());
    }

    /**
     * Test field erase credentials
     *
     * @return void
     */
    public function testEraseCredentials() {
        $user = new User();
        $this->assertNull($user->eraseCredentials());
    }

    /**
     * Test field get tasks
     *
     * @return void
     */
    public function testGetTasks()
    {
        $user = new User();
        $task = new Task();
        $user->addTask($task);
        $this->assertEquals($task, $user->getTasks()[0]);
    }

    /**
     * Test salt field
     *
     * @return void
     */
    public function testSalt(): void
    {
        $user = new User();
        $this->assertNull($user->getSalt());
    }

}