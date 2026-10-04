<?php // MonthlyStatisticsExport.php

declare(strict_types=1);

namespace App\Exports;

use App\Models\AcademicPeriod;
use App\Services\Reports\MonthlyStatisticsService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MonthlyStatisticsExport implements FromCollection, WithHeadings
{
    public function __construct(private readonly AcademicPeriod $period) {}

    public function collection()
    {
        $rows = collect();

        foreach (app(MonthlyStatisticsService::class)->get($this->period) as $stageName => $gradeRows) {
            foreach ($gradeRows as $row) {
                $rows->push([
                    $stageName,
                    $row->grade_name,
                    $row->students_count,
                    (float) $row->parent_amount,
                    (float) $row->support_amount,
                    (float) $row->total_amount,
                ]);
            }
        }

        return $rows;
    }

    public function headings(): array
    {
        return ['المرحلة', 'الصف', 'عدد الطلاب', 'المطلوب من أولياء الأمور', 'الدعم', 'الإجمالي'];
    }
}