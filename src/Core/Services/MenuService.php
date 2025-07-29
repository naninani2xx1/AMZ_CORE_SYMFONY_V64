<?php

namespace App\Core\Services;

use App\Core\Entity\Menu;
use App\Core\Repository\MenuRepository;

class MenuService
{

    private readonly MenuRepository $menuRepository;
    public function __construct(
         MenuRepository $menuRepository,
    )
    {
        $this->menuRepository = $menuRepository;
    }

    public function findOneById(int $id): ?Menu
    {
        return $this->menuRepository->find($id);
    }

    public function findMenusByPosition(string $position): ?array
    {
        $menuParent = $this->menuRepository->findOneByPosition($position);
        return $this->menuRepository->findMenusByParent($menuParent);
    }
}