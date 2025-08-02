<?php

namespace App\Twig\Runtime;

use App\Core\Services\CategoryService;
use Twig\Extension\RuntimeExtensionInterface;

class ContactTopicExtensionRuntime implements RuntimeExtensionInterface
{
    private CategoryService $categoryService;
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
        // Inject dependencies if needed
    }

    public function getAllContactTopic()
    {
        return $this->categoryService->findAllContactTopic();
    }
}
