<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use DateTime;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{

    public function testTask(): void
    {
        $task = new Task();
        $task->setTitle('Tâche n°1');
        $task->setContent('Contenu de la tâche n°1');
        $task->setCreatedAt(new DateTime());
        $task->setUser(new User());
        $task->toggle(true);

        $this->assertEquals('Tâche n°1', $task->getTitle());
        $this->assertEquals('Contenu de la tâche n°1', $task->getContent());
        $this->assertInstanceOf(DateTime::class, $task->getCreatedAt());
        $this->assertInstanceOf(User::class, $task->getUser());
        $this->assertTrue($task->isDone());
    }
}