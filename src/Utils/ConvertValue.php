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


    public function standardizationSlug($getSlug): string
    {
        return $this->slugger->slug($getSlug);
    }
}