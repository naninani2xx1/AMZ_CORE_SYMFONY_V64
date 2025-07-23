<?php
namespace App\Twig\Runtime;

use App\Core\Entity\Menu;
use App\Services\MenuService;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\RuntimeExtensionInterface;



class MenuExtensionRuntime implements RuntimeExtensionInterface
{

  private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    public function renderMenu(): array
    {
        return $this->em->getRepository(Menu::class)->findBy(
            ['isArchived' => false],
            ['sortOrder' => 'ASC']
        );
    }
}
