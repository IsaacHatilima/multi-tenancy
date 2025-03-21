<?php

namespace App\Enums;

enum TaskEscalation: string
{
    case LEVEL1 = 'level1';
    case LEVEL2 = 'level2';
    case LEVEL3 = 'level3';

    public static function fromString(?string $value): ?self
    {
        return $value ? self::tryFrom($value) : null;
    }
}
