<?php

namespace App\Security\Voter;

use App\Core\DataType\RoleDataType;
use App\Core\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class UserVoter extends Voter
{
    public const EDIT = 'POST_EDIT';
    public const VIEW = 'POST_VIEW';
    public const DELETE = 'POST_DELETE';

    public function __construct(
        private readonly AccessDecisionManagerInterface $decisionManager,
    )
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        if(self::VIEW == $attribute && is_null($subject)) return true;
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::DELETE])
            && $subject instanceof User;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }
        /** @var User $user */
        $user = $subject;
        // ... (check conditions and return true to grant permission) ...
        return match ($attribute) {
            self::EDIT,
            self::DELETE=> $this->canEditDelete($token, $user),
            self::VIEW => $this->canView($token),
            default => false,
        };

    }

    private function canEditDelete(TokenInterface $token, User $user): bool
    {
        return $this->decisionManager->decide($token, [RoleDataType::ROLE_ADMIN]);
    }

    private function canView(TokenInterface $token): bool
    {
        return $this->decisionManager->decide($token, [RoleDataType::ROLE_ADMIN]);
    }

}
