<?php

namespace App\Utils;

class ConvertValue
{

    public static function standardizationDash(string $key): string
    {
        return preg_replace('/\s+/', '-', $key);
    }
    public static function standardizationSpace(string $input): string
    {
        return preg_replace('/\s+/', '', $input);
    }
}