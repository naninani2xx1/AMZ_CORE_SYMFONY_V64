<?php

namespace App\Twig\Runtime;

use App\Core\Services\QaService;
use Twig\Extension\RuntimeExtensionInterface;

class QaExtensionRuntime implements RuntimeExtensionInterface
{
    private QaService $qaService;
    public function __construct(QaService $qaService)
    {
        $this->qaService = $qaService;
        // Inject dependencies if needed
    }

    public function getAllQas()
    {
        return $this->qaService->findAll();
    }
}
