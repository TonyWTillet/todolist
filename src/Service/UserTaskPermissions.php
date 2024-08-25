<?php

namespace App\Service;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

class UserTaskPermissions
{
    /**
     * @param UserInterface $user
     * @param Task|User $taskUser
     * @return bool
     */
    public static function isOwner(UserInterface $user, Task $taskUser): bool
    {
        return $user === $taskUser->getUser();
    }

    /**
     * @param UserInterface $user
     * @return bool
     */
    public static function isAdmin(UserInterface $user): bool
    {
        return in_array('ROLE_ADMIN', $user->getRoles());
    }
}