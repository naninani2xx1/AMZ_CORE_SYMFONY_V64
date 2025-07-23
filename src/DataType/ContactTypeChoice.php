<?php
namespace App\DataType;

Class ContactTypeChoice {
    public const COPPERATE= 'Hợp tác';
    public const TRADE = 'Mua bán';
     public const CHOICES = [
         self::TRADE,
         self::COPPERATE
     ];
}