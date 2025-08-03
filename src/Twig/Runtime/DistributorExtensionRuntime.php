<?php

namespace App\Twig\Runtime;

use App\Services\DistributorService;
use Twig\Extension\RuntimeExtensionInterface;

class DistributorExtensionRuntime implements RuntimeExtensionInterface
{
    private DistributorService $distributorService;
    public function __construct(DistributorService $distributorService)
    {
        $this->distributorService = $distributorService;
        // Inject dependencies if needed
    }

    public function getAllDistributors(): array
    {
        return $this->distributorService->findAll();
    }
}
