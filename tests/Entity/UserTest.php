<?php 

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testGetId()
    {
        $user = new User();
        $this->assertNull($user->getId());
    }

    public function testGetUsername()
    {
        $user = new User();
        $this->assertNull($user->getUsername());
    }

    public function testSetUsername()
    {
        $user = new User();
        $username = "john_doe";
        $this->assertInstanceOf(User::class, $user->setUsername($username));
        $this->assertEquals($username, $user->getUsername());
    }

    public function testGetPassword()
    {
        $user = new User();
        $this->assertNull($user->getPassword());
    }

    public function testSetPassword()
    {
        $user = new User();
        $password = "password123";
        $this->assertInstanceOf(User::class, $user->setPassword($password));
        $this->assertEquals($password, $user->getPassword());
    }

    public function testGetEmail()
    {
        $user = new User();
        $this->assertNull($user->getEmail());
    }

    public function testSetEmail()
    {
        $user = new User();
        $email = "john@example.com";
        $this->assertInstanceOf(User::class, $user->setEmail($email));
        $this->assertEquals($email, $user->getEmail());
    }

    public function testGetRoles()
    {
        $user = new User();
        $this->assertIsArray($user->getRoles());
        $this->assertEmpty($user->getRoles());
    }

    public function testSetRoles()
    {
        $user = new User();
        $roles = ['ROLE_USER'];
        $this->assertInstanceOf(User::class, $user->setRoles($roles));
        $this->assertEquals($roles, $user->getRoles());
    }

    public function testGetSalt()
    {
        $user = new User();
        $this->assertNull($user->getSalt());
    }

    public function testEraseCredentials()
    {
        $user = new User();
        $this->assertNull($user->eraseCredentials());
    }

    public function testGetTasks()
    {
        $user = new User();
        $this->assertInstanceOf(\Doctrine\Common\Collections\Collection::class, $user->getTasks());
        $this->assertEmpty($user->getTasks());
    }

    public function testAddTask()
    {
        $user = new User();
        $task = new Task();
        $this->assertInstanceOf(User::class, $user->addTask($task));
        $this->assertTrue($user->getTasks()->contains($task));
        $this->assertEquals($user, $task->getUser());
    }

    public function testRemoveTask()
    {
        $user = new User();
        $task = new Task();
        $user->addTask($task);
        $this->assertInstanceOf(User::class, $user->removeTask($task));
        $this->assertFalse($user->getTasks()->contains($task));
        $this->assertNull($task->getUser());
    }
}