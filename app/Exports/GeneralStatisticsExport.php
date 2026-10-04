<?php // GeneralStatisticsExport.php

declare(strict_types=1);

namespace App\Exports;

use App\Services\Reports\GeneralStatisticsService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class GeneralStatisticsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $rows = collect();

        foreach (app(GeneralStatisticsService::class)->get() as $stage) {
            foreach ($stage->grades as $grade) {
                $rows->push([$stage->name, $grade->name, $grade->students_count]);
            }
        }

        return $rows;
    }

    public function headings(): array
    {
        return ['المرحلة', 'الصف', 'عدد الطلاب'];
    }
}