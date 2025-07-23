<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class UserVoter extends Voter
{
    public const EDIT = 'USER_EDIT';
    public const VIEW = 'USER_VIEW';
    public const CREATE = 'USER_CREATE';
    public const DELETE = 'USER_DELETE';
    protected function supports(string $attribute, mixed $subject): bool
    {
       if($attribute === self::VIEW) {
           return true;
       }
        return in_array($attribute, [self::EDIT, self::VIEW, self::CREATE, self::DELETE  ])
            && $subject instanceof \App\Core\Entity\User;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($user);
            case self::CREATE:
                return $this->canCreate($user);
                case self::EDIT:
                return $this->canEdit($user);
            case self::DELETE:
                return $this->canDelete($user);
        }

        return false;
    }

    public function canView(UserInterface $user): bool{
        return in_array('ROLE_ADMIN', $user->getRoles());
    }
    public function canCreate(UserInterface $user): bool{
        return in_array('ROLE_ADMIN', $user->getRoles());
    }
    public function canEdit(UserInterface $user): bool{
        return in_array('ROLE_ADMIN', $user->getRoles());
    }
    public function canDelete(UserInterface $user): bool{
        return in_array('ROLE_ADMIN', $user->getRoles());
    }

}
