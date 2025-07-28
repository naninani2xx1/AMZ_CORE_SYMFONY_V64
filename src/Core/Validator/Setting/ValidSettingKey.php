<?php

namespace App\Core\Validator\Setting;

use Doctrine\Common\Annotations\Annotation\Attribute;
use Symfony\Component\Validator\Constraint;

/**
 * @Attribute
 */
class ValidSettingKey extends Constraint
{
    public string $message = 'Invalid setting key: only hyphens allowed.';
}