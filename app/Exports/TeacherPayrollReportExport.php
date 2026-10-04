<?php // TeacherPayrollReportExport.php

declare(strict_types=1);

namespace App\Exports;

use App\Models\AcademicPeriod;
use App\Services\Reports\TeacherPayrollReportService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TeacherPayrollReportExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(private readonly AcademicPeriod $period) {}

    public function collection()
    {
        return app(TeacherPayrollReportService::class)->get($this->period);
    }

    public function headings(): array
    {
        return ['المدرس', 'عدد الطلاب', 'المستحق', 'الدعم', 'الإجمالي', 'نسبة المركز', 'نصيب المركز', 'نصيب المدرس'];
    }

    public function map($row): array
    {
        return [
            $row->teacher->name,
            $row->students_count,
            $row->fee_amount,
            $row->support_amount,
            $row->gross_amount,
            $row->center_percentage,
            $row->center_amount,
            $row->teacher_amount,
        ];
    }
}