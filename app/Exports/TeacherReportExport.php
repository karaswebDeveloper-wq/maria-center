<?php // TeacherReportExport.php

declare(strict_types=1);

namespace App\Exports;

use App\Services\Reports\TeacherReportService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TeacherReportExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(private readonly array $filters) {}

    public function collection()
    {
        return app(TeacherReportService::class)->get($this->filters);
    }

    public function headings(): array
    {
        return ['الطالب', 'المرحلة', 'الصف', 'المادة', 'المدرس', 'المدة', 'المطلوب من ولي الأمر', 'الدعم', 'الإجمالي'];
    }

    public function map($row): array
    {
        return [
            $row->student->name,
            $row->student->grade->stage->name,
            $row->student->grade->name,
            $row->subject->name,
            $row->teacher->name,
            $row->duration_type->label(),
            $row->required_from_parent,
            (float) $row->support_amount,
            (float) $row->fee_amount,
        ];
    }
}