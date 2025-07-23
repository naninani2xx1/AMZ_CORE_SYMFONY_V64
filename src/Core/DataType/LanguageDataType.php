<?php

namespace App\Core\DataType;

class LanguageDataType
{
    const VIETNAMESE_LANGUAGE_CODE = 'vi';
    const ENGLISH_LANGUAGE_CODE = 'en';


    public static function getNameByCode(string $code): string
    {
        return match ($code) {
            self::VIETNAMESE_LANGUAGE_CODE => "Vietnamese",
            self::ENGLISH_LANGUAGE_CODE => "English",
            default => "Not supported for ". $code,
        };
    }
}