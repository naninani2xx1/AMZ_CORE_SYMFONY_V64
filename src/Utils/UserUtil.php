<?php

namespace App\Utils;

class UserUtil
{

    public static function standardization(string $input): string
    {
        return preg_replace('/\s+/', '', $input);
    }

}