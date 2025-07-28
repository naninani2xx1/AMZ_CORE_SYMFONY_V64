<?php

namespace App\Core\Validator\User;
use App\Core\Services\UserService;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ValidUsernameValidator extends ConstraintValidator
{
    public function __construct(
        private readonly UserService $userService
    )
    {
    }

    public function validate($value, Constraint $constraint): void
    {
        if (null === $value || '' === $value) {
            return;
        }

        if (!preg_match('/^[a-zA-Z0-9\-_]+$/', $value)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
            return;
        }

        if (in_array(strtolower($value), array_map('strtolower', $constraint->forbiddenWords))) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }

        if($this->userService->isUsernameAlready($value)){
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }

    }
}