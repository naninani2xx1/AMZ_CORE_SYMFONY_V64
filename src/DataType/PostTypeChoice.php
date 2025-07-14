<?php
namespace App\DataType;

class PostTypeChoice
{

    public const POST_TYPE_HEALTH = 1;
    public const POST_TYPE_KITCHEN = 2;
    public const POST_TYPE_NATURAL_VALUE = 3;
    public const POST_TYPE_NATURAL = 4;
    public const POST_TYPE_PRODUCT = 5;
    public const POST_TYPE_TRADE_AND_SERVICE = 6;

    public const TYPE_LABELS = [
        self::POST_TYPE_HEALTH => 'Sức khỏe',
        self::POST_TYPE_KITCHEN => 'Vào bếp',
        self::POST_TYPE_NATURAL_VALUE => 'Giá trị dinh dưỡng',
        self::POST_TYPE_PRODUCT => 'Về sản ',
        self::POST_TYPE_TRADE_AND_SERVICE => 'Thương mại và dịch vụ',
    ];
}