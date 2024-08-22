<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class AppFixtures extends Fixture
{
    public function __construct(private PasswordHasherFactoryInterface $passwordHasherFactory,)
    {
    }
    public function load(ObjectManager $manager): void
    {
        $t = 5;
        for ($i = 0; $i < $t; $i++) {
            $task = new Task();
            $task->setTitle('Tâche n°' . $i);
            $task->setContent('Contenu de la tâche n°' . $i);
            if ($i % 2 === 0) {
                $task->toggle(true);
            } else {
                $task->toggle(false);
            }
            $manager->persist($task);
        }

        $user = new User();
        $user->setUsername('User');
        $user->setEmail('user@gmail.com');
        $user->setPassword($this->passwordHasherFactory->getPasswordHasher(User::class)->hash('user'));
        $user->setRoles(['ROLE_USER']);
        $manager->persist($user);

        $admin = new User();
        $admin->setUsername('Admin');
        $admin->setEmail('admin@gmail.com');
        $admin->setPassword($this->passwordHasherFactory->getPasswordHasher(User::class)->hash('admin'));
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        $manager->flush();
    }
}
