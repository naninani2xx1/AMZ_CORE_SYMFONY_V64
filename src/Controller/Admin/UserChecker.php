<?php

namespace App\Controller\Admin;

use App\Core\Entity\User;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;


class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }

        if ($user->isArchived()==1) {
            throw new CustomUserMessageAccountStatusException('Tài khoản của bạn đã bị vô hiệu hóa.');
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {

    }
}
