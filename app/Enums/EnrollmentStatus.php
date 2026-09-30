<?php // EnrollmentStatus.php

declare(strict_types=1);

namespace App\Enums;

enum EnrollmentStatus: string
{
    case Active = 'active';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'نشط',
            self::Cancelled => 'ملغي',
        };
    }
}