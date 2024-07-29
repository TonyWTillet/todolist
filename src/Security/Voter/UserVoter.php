<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

class UserVoter extends BaseVoter
{
    public const VIEW = 'USER_VIEW';
    public const EDIT = 'USER_EDIT';
    public const DELETE = 'USER_DELETE';

    protected function supportsClass($subject): bool
    {
        return $subject instanceof User;
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

    protected function canView($user, UserInterface $currentUser): bool
    {
        return in_array('ROLE_ADMIN', $currentUser->getRoles()) || $currentUser === $user;
    }

    protected function canEdit($user, UserInterface $currentUser): bool
    {
        return in_array('ROLE_ADMIN', $currentUser->getRoles()) || $currentUser === $user;
    }

    protected function canDelete($user, UserInterface $currentUser): bool
    {
        return in_array('ROLE_ADMIN', $currentUser->getRoles());
    }
}
