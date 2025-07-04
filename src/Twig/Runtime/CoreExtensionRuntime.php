<?php

namespace App\Twig\Runtime;

use Twig\Extension\RuntimeExtensionInterface;

class CoreExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct(
    )
    {
        // Inject dependencies if needed
    }

    public function jsonDecode($value): string
    {
        return json_decode($value, true);
    }

    public function hasValue($value): bool
    {
        return !empty($value);
    }
}
