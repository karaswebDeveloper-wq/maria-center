<?php // TeacherPayrollReport.php

declare(strict_types=1);

namespace App\Livewire\Reports;

use App\Models\AcademicPeriod;
use App\Services\Reports\TeacherPayrollReportService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class TeacherPayrollReport extends Component
{
    public ?int $periodId = null;

    public function render()
    {
        $rows = $this->periodId
            ? app(TeacherPayrollReportService::class)->get(AcademicPeriod::findOrFail($this->periodId))
            : collect();

        return view('reports.teacher-payroll-report', [
            'rows' => $rows,
            'periods' => AcademicPeriod::query()->orderByDesc('year')->orderByDesc('month')->get(),
        ]);
    }
}
