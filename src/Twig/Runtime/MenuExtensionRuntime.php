<?php

namespace App\Twig\Runtime;

use App\Core\Services\MenuService;
use Twig\Extension\RuntimeExtensionInterface;

class MenuExtensionRuntime implements RuntimeExtensionInterface
{
    private MenuService $menuService;
    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
        // Inject dependencies if needed
    }

    public function findMenusByPosition(string $position): array
    {
        return $this->menuService->findMenusByPosition($position);
    }
}
