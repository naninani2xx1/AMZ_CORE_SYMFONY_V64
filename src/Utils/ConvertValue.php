<?php

namespace App\Utils;

use Symfony\Component\String\Slugger\SluggerInterface;

class ConvertValue
{
    private SluggerInterface $slugger;
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public static function standardizationDash(string $key): string
    {
        return preg_replace('/\s+/', '-', $key);
    }
    public static function standardizationSpace(string $input): string
    {
        return preg_replace('/\s+/', '', $input);
    }

    public function standardizationSlug($getSlug): string
    {
        return $this->slugger->slug($getSlug);
    }
}