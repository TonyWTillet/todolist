<?php

namespace App\Security\Voter;

use App\Entity\Task;
use Symfony\Component\Security\Core\User\UserInterface;

class TaskVoter extends BaseVoter
{
    public const VIEW = 'TASK_VIEW';
    public const EDIT = 'TASK_EDIT';
    public const DELETE = 'TASK_DELETE';

    protected function supportsClass($subject): bool
    {
        return $subject instanceof Task;
    }

    protected function getSupportedAttributes(): array
    {
        return [self::VIEW, self::EDIT, self::DELETE];
    }

    protected function getViewAttribute(): string
    {
        return self::VIEW;
    }

    protected function getEditAttribute(): string
    {
        return self::EDIT;
    }

    protected function getDeleteAttribute(): string
    {
        return self::DELETE;
    }

    protected function canView($task, UserInterface $user): bool
    {
        return in_array('ROLE_USER', $user->getRoles()) && $task->getUser() === $user;
    }

    protected function canEdit($task, UserInterface $user): bool
    {
        return in_array('ROLE_USER', $user->getRoles()) && $task->getUser() === $user;
    }

    protected function canDelete($task, UserInterface $user): bool
    {
        return in_array('ROLE_USER', $user->getRoles()) && $task->getUser() === $user;
    }
}
