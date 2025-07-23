<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class SettingVoter extends Voter
{
    public const EDIT = 'SETTING_EDIT';
    public const VIEW = 'SETTING_VIEW';

    public const DELETE = 'SETTING_DELETE';

    public const CREATE = 'SETTING_CREATE';
    protected function supports(string $attribute, mixed $subject): bool
    {
        if($attribute == self::VIEW) {
            return true;
        }

        return in_array($attribute, [self::EDIT, self::VIEW, self::DELETE, self::CREATE])
            && $subject instanceof \App\Core\Entity\Setting;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }
        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($user);
            case self::VIEW:
                return $this->canView($user);
            case self::DELETE:
                return $this->canDelete($user);
            case self::CREATE:
                return $this->canCreate($user);
        }
        return false;
    }
    private function canEdit(UserInterface $user): bool
    {
        return in_array('ROLE_ADMIN', $user->getRoles());
    }

    private function canDelete(UserInterface $user): bool
    {
        return in_array('ROLE_ADMIN', $user->getRoles());
    }

    private function canView(UserInterface $user): bool
    {
        return in_array('ROLE_ADMIN', $user->getRoles());
    }

    private function canCreate(UserInterface $user): bool
    {
        return in_array('ROLE_ADMIN', $user->getRoles());
    }

}
