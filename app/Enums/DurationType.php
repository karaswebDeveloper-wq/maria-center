<?php // DurationType.php

declare(strict_types=1);

namespace App\Enums;

enum DurationType: string
{
    case FullMonth = 'full_month';
    case HalfMonth = 'half_month';

    public function label(): string
    {
        return match ($this) {
            self::FullMonth => 'شهر كامل',
            self::HalfMonth => 'نصف شهر',
        };
    }
}