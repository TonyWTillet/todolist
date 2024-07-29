<?php

// src/DataFixtures/TaskFixtures.php
namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TaskFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        // Récupérer la référence de l'utilisateur
        $user = $this->getReference('regular-user');

        for ($i = 1; $i <= 10; $i++) {
            $task = new Task();
            $task->setTitle('Task ' . $i);
            $task->setContent('Content of task ' . $i);
            $task->setUser($user);

            $manager->persist($task);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}