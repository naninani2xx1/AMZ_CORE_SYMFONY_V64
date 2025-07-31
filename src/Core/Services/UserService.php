<?php

namespace App\Core\Services;

use App\Core\Entity\User;
use App\Core\Repository\UserRepository;

class UserService
{
    public function __construct(
        private readonly UserRepository $userRepository,
    )
    {
    }

    public function findOneUser(int $id): ?User
    {
        return $this->userRepository->find($id);
    }

    public function findOneByUsername(string $username): ?User
    {
        return $this->userRepository->findOneBy(['username' => $username]);
    }

    public function isUsernameAlready(string $username): bool
    {
        $user = $this->userRepository->findOneBy(['username' => $username]);
        return $user instanceof User;
    }
}