<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\AcademicPeriod;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class AcademicPeriodSeeder extends Seeder
{
    private const ARABIC_MONTHS = [
        1 => 'يناير', 2 => 'فبراير', 3 => 'مارس', 4 => 'أبريل',
        5 => 'مايو', 6 => 'يونيو', 7 => 'يوليو', 8 => 'أغسطس',
        9 => 'سبتمبر', 10 => 'أكتوبر', 11 => 'نوفمبر', 12 => 'ديسمبر',
    ];

    public function run(): void
    {
        // Seeds one school year: August through the following May.
        $start = Carbon::create(2026, 8, 1);

        for ($i = 0; $i < 10; $i++) {
            $date = $start->copy()->addMonthsNoOverflow($i);

            AcademicPeriod::query()->updateOrCreate(
                ['year' => $date->year, 'month' => $date->month],
                [
                    'name' => self::ARABIC_MONTHS[$date->month].' '.$date->year,
                    'starts_at' => $date->copy()->startOfMonth(),
                    'ends_at' => $date->copy()->endOfMonth(),
                    'is_closed' => false,
                ],
            );
        }
    }
}