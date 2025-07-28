<?php

namespace App\Utils;

class BlockFieldMap
{
    public static function getFieldsByKind(string $kind): array
    {
        return match($kind) {
            'contact_info' => ['address', 'phone', 'email', 'moreLinks'],
            'video_block' => ['videoTitle', 'videoUrl', 'thumbnail'],
            default => [],
        };
    }
}
