<?php

namespace App\Twig\Runtime;

use App\Core\DataType\RoleDataType;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;
use Twig\Extension\RuntimeExtensionInterface;

class CoreExtensionRuntime implements RuntimeExtensionInterface
{
    private RequestStack $requestStack;
    private Environment $environment;
    public function __construct(
        RequestStack $requestStack,
        Environment $environment
    )
    {
        // Inject dependencies if needed
        $this->requestStack = $requestStack;
        $this->environment = $environment;
    }

    public function jsonDecode($value): array
    {
        return json_decode($value, true);
    }

    public function hasValue($value): bool
    {
        return !empty($value);
    }

    public function isRouteActive($route): string
    {
        $request = $this->requestStack->getCurrentRequest();
        $currentRoute = $request->attributes->get('_route');
        return $currentRoute == $route ? "active" : "";
    }

    public function getRolesRawHtml(array $roles): string
    {
        $arr = [];
        foreach ($roles as $role) {
            $arr[] = RoleDataType::getNameByRole($role);
        }
        return $this->environment->render('Admin/partials/roles_raw.html.twig', compact('arr'));
    }
}
