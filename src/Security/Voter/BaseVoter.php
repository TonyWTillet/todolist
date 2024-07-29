<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

abstract class BaseVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, $this->getSupportedAttributes()) && $this->supportsClass($subject);
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case $this->getViewAttribute():
                $result = $this->canView($subject, $user);
                break;
            case $this->getEditAttribute():
                $result = $this->canEdit($subject, $user);
                break;
            case $this->getDeleteAttribute():
                $result = $this->canDelete($subject, $user);
                break;
        }

        return $result;
    }

    abstract protected function supportsClass($subject): bool;
    abstract protected function getSupportedAttributes(): array;
    abstract protected function getViewAttribute(): string;
    abstract protected function getEditAttribute(): string;
    abstract protected function getDeleteAttribute(): string;
    abstract protected function canView($subject, UserInterface $user): bool;
    abstract protected function canEdit($subject, UserInterface $user): bool;
    abstract protected function canDelete($subject, UserInterface $user): bool;
}
