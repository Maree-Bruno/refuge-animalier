<?php

namespace App\Enums;

class AnimalStatus
{

    public const VALIDATED = 'Validated';
    public const IN_PROGRESS = 'In progress';
    public const ADOPTED = 'Adopted';

    public static function values(): array
    {
        return [
            self::VALIDATED,
            self::IN_PROGRESS,
            self::ADOPTED,
        ];
    }
}
