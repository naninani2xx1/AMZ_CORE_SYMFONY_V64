<?php

namespace App\Security\Voter;

use App\Core\DataType\RoleDataType;
use App\Core\Entity\Page;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class PageVoter extends Voter
{
    public const EDIT = 'POST_EDIT';
    public const ADD = 'POST_ADD';
    public const VIEW = 'POST_VIEW';

    public function __construct(
        private readonly AccessDecisionManagerInterface $accessDecisionManager,
    )
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::VIEW, self::ADD])
            && $subject instanceof Page;
    }

    // this method returns true if the voter applies to the given attribute;
    // if it returns false, Symfony won't call it again for this attribute
    public function supportsAttribute(string $attribute): bool
    {
        return in_array($attribute, [self::VIEW, self::EDIT, self::ADD], true);
    }

    // this method returns true if the voter applies to the given object class/type;
    // if it returns false, Symfony won't call it again for that type of object
    public function supportsType(string $subjectType): bool
    {
        // you can't use a simple Post::class === $subjectType comparison
        // because the subject type might be a Doctrine proxy class
        return is_a($subjectType, Page::class, true);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        if ($this->accessDecisionManager->decide($token, [RoleDataType::ROLE_ADMIN])) {
            return true;
        }

        /** @var Page $page */
        $page = $subject;

        return match($attribute) {
            self::ADD => $this->canAdd($page, $user, $token),
//            self::VIEW => $this->canView($page, $user),
//            self::EDIT => $this->canEdit($page, $user, $vote),
            default => throw new \LogicException('This code should not be reached!')
        };
    }

    private function canAdd(Page $page, UserInterface $user, TokenInterface $token): bool
    {
        return $this->accessDecisionManager->decide($token, [RoleDataType::ROLE_ADMIN_PAGE]);
    }
}
