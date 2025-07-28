<?php

namespace App\Security\Voter;

use App\Core\DataType\RoleDataType;
use App\Core\Entity\Setting;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class SettingVoter extends Voter
{
    public const EDIT = 'POST_EDIT';
    public const DELETE = 'POST_DELETE';
    public const VIEW = 'POST_VIEW';

    public function __construct(
        private readonly AccessDecisionManagerInterface $accessDecisionManager
    )
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        if($attribute === self::VIEW)
            return true;
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::DELETE])
            && $subject instanceof Setting;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }
        /** @var Setting $setting */
        $setting = $subject;
        // ... (check conditions and return true to grant permission) ...
        return match ($attribute) {
            self::DELETE, self::EDIT => $this->canEditAndDelete($token, $setting),
            self::VIEW => $this->canView($token),
            default => false,
        };

    }

    private function canView(TokenInterface $token): bool
    {
        return $this->accessDecisionManager->decide($token, [RoleDataType::ROLE_ADMIN_SETTING]);
    }

    private function canEditAndDelete(TokenInterface $token, Setting $setting): bool
    {
        return $this->accessDecisionManager->decide($token, [RoleDataType::ROLE_ADMIN_SETTING]);
    }
}
