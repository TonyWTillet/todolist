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

    public function login($type, bool $useFixtures = false): void
    {
        $entityManager = $this->client->getContainer()
            ->get('doctrine')
            ->getManager();

        if ($type === 'admin') {
            $user = $entityManager->getRepository(User::class)->findOneBy(['username' => 'admin']);
            if (!$user) {
                $user = new User();
                $user->setUsername('admintest');
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
                $user->setUsername('usertest');
                $user->setEmail('usertest@gmail.com');
                $user->setPassword('user');
                $user->setRoles(['ROLE_USER']);
                $entityManager->persist($user);
                $entityManager->flush();
            }
        }

        $this->client->loginUser($user);
    }

    public function loginByUsername(string $username): void
    {
        $entityManager = $this->client->getContainer()
            ->get('doctrine')
            ->getManager();

        $user = $entityManager->getRepository(User::class)->findOneBy(['username' => $username]);
        $this->client->loginUser($user);
    }
}
