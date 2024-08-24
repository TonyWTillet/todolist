<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class AppFixtures extends Fixture
{
    public function __construct(private PasswordHasherFactoryInterface $passwordHasherFactory)
    {
    }
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername('User');
        $user->setEmail('user@gmail.com');
        $user->setPassword($this->passwordHasherFactory->getPasswordHasher(User::class)->hash('user'));
        $user->setRoles(['ROLE_USER']);
        $manager->persist($user);

        $user2 = new User();
        $user2->setUsername('user2');
        $user2->setEmail('usernotask@gmail.com');
        $user2->setPassword($this->passwordHasherFactory->getPasswordHasher(User::class)->hash('user'));
        $user2->setRoles(['ROLE_USER']);
        $manager->persist($user2);

        $admin = new User();
        $admin->setUsername('Admin');
        $admin->setEmail('admin@gmail.com');
        $admin->setPassword($this->passwordHasherFactory->getPasswordHasher(User::class)->hash('admin'));
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        $ano = new User();
        $ano->setUsername('Anonyme');
        $ano->setEmail('ano@gmail.com');
        $ano->setPassword($this->passwordHasherFactory->getPasswordHasher(User::class)->hash('ano'));
        $ano->setRoles(['ROLE_USER']);
        $manager->persist($ano);

        $t = 16;
        for ($i = 0; $i < $t; $i++) {
            $task = new Task();
            $task->setTitle('Tâche n°' . $i);
            $task->setContent('Contenu de la tâche n°' . $i);
            if ($i < 5) {
                if ($i%2 === 0) {
                    $task->toggle(false);
                } else {
                    $task->toggle(true);
                }
                $task->setUser($user);
            } elseif ($i < 10) {
                if ($i%2 === 0) {
                    $task->toggle(false);
                } else {
                    $task->toggle(true);
                }
                $task->setUser($admin);

            } elseif ($i < 15) {
                if ($i%2 === 0) {
                    $task->toggle(false);
                } else {
                    $task->toggle(true);
                }
                $task->setUser($ano);
            } else {
                $task = new Task();
                $task->setTitle('Tâche n°' . $i);
                $task->setContent('Contenu de la tâche n°' . $i);
                $task->setUser($user2);
            }
            $manager->persist($task);
        }





        $manager->flush();
    }
}
