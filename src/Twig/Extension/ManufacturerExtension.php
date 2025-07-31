<?php
namespace App\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use App\Twig\Runtime\ManufacturerExtensionRunTime;

class ManufacturerExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('manufacturer_data', [ManufacturerExtensionRunTime::class, 'getManufacturers']),
        ];
    }
}