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

    public function doSomething($value)
    {
        // ...
    }

    public function supports($value): bool
    {
        return true;
    }
}
