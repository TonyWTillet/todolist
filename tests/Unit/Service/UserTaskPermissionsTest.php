<?php

namespace App\Tests\Unit\Service;

use App\Entity\Task;
use App\Entity\User;
use App\Service\UserTaskPermissions;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\User\UserInterface;

class UserTaskPermissionsTest extends KernelTestCase
{
    /**
     *  Test if the user is the owner of the task
     *
     * @return void
     */
    public function testIsOwnerWithTask(): void
    {
        $user = new User();
        $user->setUsername('test_user');

        $task = new Task();
        $task->setUser($user);

        $this->assertTrue(UserTaskPermissions::isOwner($user, $task));
    }

    /**
     * Test if the user is not the owner of the task
     *
     * @return void
     */
    public function testIsNotOwner(): void
    {
        $user1 = new User();
        $user1->setUsername('user1');

        $user2 = new User();
        $user2->setUsername('user2');

        $task = new Task();
        $task->setUser($user1);

        $this->assertFalse(UserTaskPermissions::isOwner($user2, $task));
    }

    /**
     * Test if the user is an admin
     *
     * @return void
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testIsAdmin(): void
    {
        $userMock = $this->createMock(UserInterface::class);

        $userMock->method('getRoles')->willReturn(['ROLE_ADMIN']);

        $this->assertTrue(UserTaskPermissions::isAdmin($userMock));
    }

    /**
     * Test if the user is not an admin
     *
     * @return void
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testIsNotAdmin(): void
    {
        $userMock = $this->createMock(UserInterface::class);

        $userMock->method('getRoles')->willReturn(['ROLE_USER']);

        $this->assertFalse(UserTaskPermissions::isAdmin($userMock));
    }
}