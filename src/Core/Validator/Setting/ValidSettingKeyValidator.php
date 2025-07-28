<?php

namespace App\Core\Validator\Setting;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ValidSettingKeyValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (null === $value || '' === $value) {
            return;
        }

        if (preg_match('/^[a-zA-Z0-9\-]+$/', $value) === 0) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}