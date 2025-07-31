<?php

namespace App\Twig\Runtime;

use App\Core\DataType\PostStatusType;
use Twig\Extension\RuntimeExtensionInterface;

class PostExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct()
    {
        // Inject dependencies if needed
    }

    public function getNamePublished($value): string
    {
        return PostStatusType::getNameByPublishType($value);
    }
}
