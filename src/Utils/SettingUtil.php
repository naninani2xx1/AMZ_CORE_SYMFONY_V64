<?php

namespace App\Utils;

class SettingUtil
{

    public static function convertSettingKey(string $key): string
    {
        return preg_replace('/\s+/', '-', $key);
    }

}