<?php

namespace App\Core\Validator\User;

use Doctrine\Common\Annotations\Annotation\Attribute;
use Symfony\Component\Validator\Constraint;

/**
 * @Attribute
 */
class ValidUsername extends Constraint
{
    public string $message = 'Invalid username';
    public array $forbiddenWords = ['root', 'admin'];
}