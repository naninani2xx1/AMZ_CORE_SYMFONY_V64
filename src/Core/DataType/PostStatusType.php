<?php

namespace App\Core\DataType;

class PostStatusType
{
    /** STATUS IS_HOT */
    const HOT_TYPE_NORMAL = 1;
    const HOT_TYPE_HOT = 2;

    /** STATUS IS_NEW */
    const NEW_TYPE_NORMAL = 1;
    const NEW_TYPE_NEW = 2;

    /** STATUS PUBLISHED */
    const PUBLISH_TYPE_PUBLISHED = 2;
    const PUBLISH_TYPE_DRAFT = 1;

    public static function getNameByPublishType(int $publishType): string
    {
        return match ($publishType) {
            self::PUBLISH_TYPE_DRAFT => "Draft",
            self::PUBLISH_TYPE_PUBLISHED => "Published",
        };
    }
}
