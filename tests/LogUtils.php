<?php

namespace App\Tests;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\BrowserKit\Cookie;

class LogUtils
{
    private $client;

    public function __construct($client)
    {
        $this->client = $client;
    }

    public function login($type): void
    {
        $credentials = ['username' => $type];

        // get doctrine
        $entityManager = $this->client->getContainer()
            ->get('doctrine')
            ->getManager();

        // get a user from database
        if ($type === 'admin') {
            $user = $entityManager->getRepository(User::class)->findOneBy(['username' => 'admin']);
            if (!$user) {
                $user = new User();
                $user->setUsername('admin');
                $user->setEmail('admintest@gmail.com');
                $user->setPassword('admin');
                $user->setRoles(['ROLE_ADMIN']);
                $entityManager->persist($user);
                $entityManager->flush();
            }
        } else {
            $user = $entityManager->getRepository(User::class)->findOneBy(['username' => 'user']);
            if (!$user) {
                $user = new User();
                $user->setUsername('user');
                $user->setEmail('usertest@gmail.com');
                $user->setPassword('user');
                $user->setRoles(['ROLE_USER']);
                $entityManager->persist($user);
                $entityManager->flush();
            }
        }

        $this->client->loginUser($user);
    }
}