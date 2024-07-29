<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    public function testGetId()
    {
        $task = new Task();
        $this->assertNull($task->getId());
    }

    public function testGetCreatedAt()
    {
        $task = new Task();
        $this->assertInstanceOf(\DateTimeInterface::class, $task->getCreatedAt());
    }

    public function testSetCreatedAt()
    {
        $task = new Task();
        $createdAt = new \DateTime();
        $this->assertInstanceOf(Task::class, $task->setCreatedAt($createdAt));
        $this->assertEquals($createdAt, $task->getCreatedAt());
    }

    public function testGetTitle()
    {
        $task = new Task();
        $this->assertNull($task->getTitle());
    }

    public function testGetContent()
    {
        $task = new Task();
        $this->assertNull($task->getContent());
    }

    public function testIsDone()
    {
        $task = new Task();
        $this->assertFalse($task->isDone());
    }

    public function testToggle()
    {
        $task = new Task();
        $this->assertInstanceOf(Task::class, $task->toggle(true));
        $this->assertTrue($task->isDone());
    }

    public function testGetUser()
    {
        $task = new Task();
        $this->assertNull($task->getUser());
    }

}