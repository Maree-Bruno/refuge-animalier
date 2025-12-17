<?php

namespace App\Enums;

enum SuitableFor: string
{
    case DOG = 'dog';
    case CAT = 'cat';
    case KID = 'kid';
    case BABY = 'baby';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
