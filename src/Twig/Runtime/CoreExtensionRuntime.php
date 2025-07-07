<?php

namespace App\Twig\Runtime;

use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\RuntimeExtensionInterface;

class CoreExtensionRuntime implements RuntimeExtensionInterface
{
    private RequestStack $requestStack;
    public function __construct(
        RequestStack $requestStack
    )
    {
        // Inject dependencies if needed
        $this->requestStack = $requestStack;
    }

    public function jsonDecode($value): string
    {
        return json_decode($value, true);
    }

    public function hasValue($value): bool
    {
        return !empty($value);
    }

    public function isRouteActive($route): bool
    {
        $request = $this->requestStack->getCurrentRequest();
        $currentRoute = $request->attributes->get('_route');
        return $currentRoute == $route;
    }
}
