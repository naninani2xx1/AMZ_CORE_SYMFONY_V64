<?php

namespace App\Twig\Runtime;

use Twig\Extension\RuntimeExtensionInterface;
use App\Repository\ManufacturerRepository;

class ManufacturerExtensionRunTime implements RuntimeExtensionInterface
{
    private $manufacturerRepository;

    public function __construct(ManufacturerRepository $manufacturerRepository)
    {
        $this->manufacturerRepository = $manufacturerRepository;
    }

    public function getManufacturers(): array
    {
        return $this->manufacturerRepository->findBy([], ['sortOrder' => 'ASC']);
    }
}
