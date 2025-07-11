<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\CoreExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class CoreExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('json_decode', [CoreExtensionRuntime::class, 'jsonDecode']),
            new TwigFilter('has_value', [CoreExtensionRuntime::class, 'hasValue']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('supports', [CoreExtensionRuntime::class, 'supports']),
            new TwigFunction('is_route_active', [CoreExtensionRuntime::class, 'isRouteActive']),
        ];
    }
}
