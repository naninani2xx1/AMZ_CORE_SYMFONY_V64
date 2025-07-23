<?php
namespace App\Twig\Extension;

use App\Twig\Runtime\MenuExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class MenuExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('render_menu', [MenuExtensionRuntime::class, 'renderMenu'], [
                'is_safe' => ['html'],
            ]),
        ];
    }
}
