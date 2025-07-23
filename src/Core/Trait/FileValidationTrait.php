<?php

namespace App\Core\Trait;

trait FileValidationTrait
{
    public function isFilePDF(string $url): bool
    {
        $arr = explode('.', $url);
        $extension = end($arr);
        return ($extension === 'pdf');
    }

    public function isValidExtension(string $extension,string | array $extensions): bool
    {
        if(is_string($extensions))
            return $extensions === $extension;

        return in_array($extension, $extensions);
    }
}