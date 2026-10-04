<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exports\GeneralStatisticsExport;
use App\Exports\MonthlyStatisticsExport;
use App\Exports\TeacherPayrollReportExport;
use App\Exports\TeacherReportExport;
use App\Models\AcademicPeriod;
use App\Services\Reports\GeneralStatisticsService;
use App\Services\Reports\MonthlyStatisticsService;
use App\Services\Reports\TeacherPayrollReportService;
use App\Services\Reports\TeacherReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportExportController extends Controller
{
    public function teacherPdf(Request $request, TeacherReportService $service)
    {
        $rows = $service->get($this->teacherFilters($request));

        return Pdf::loadView('pdf.teacher-report', ['rows' => $rows])
            ->setPaper('a4', 'landscape')
            ->download('teacher-report-'.now()->format('Y-m-d').'.pdf');
    }

    public function teacherExcel(Request $request)
    {
        return Excel::download(
            new TeacherReportExport($this->teacherFilters($request)),
            'teacher-report-'.now()->format('Y-m-d').'.xlsx'
        );
    }

    public function payrollPdf(Request $request, TeacherPayrollReportService $service)
    {
        $period = AcademicPeriod::findOrFail($request->integer('period_id'));
        $rows = $service->get($period);

        return Pdf::loadView('pdf.payroll-report', ['rows' => $rows, 'period' => $period])
            ->setPaper('a4', 'landscape')
            ->download('payroll-report-'.$period->year.'-'.$period->month.'.pdf');
    }

    public function payrollExcel(Request $request)
    {
        $period = AcademicPeriod::findOrFail($request->integer('period_id'));

        return Excel::download(
            new TeacherPayrollReportExport($period),
            'payroll-report-'.$period->year.'-'.$period->month.'.xlsx'
        );
    }

    public function monthlyPdf(Request $request, MonthlyStatisticsService $service)
    {
        $period = AcademicPeriod::findOrFail($request->integer('period_id'));
        $groups = $service->get($period);

        return Pdf::loadView('pdf.monthly-statistics', ['groups' => $groups, 'period' => $period])
            ->setPaper('a4')
            ->download('monthly-statistics-'.$period->year.'-'.$period->month.'.pdf');
    }

    public function monthlyExcel(Request $request)
    {
        $period = AcademicPeriod::findOrFail($request->integer('period_id'));

        return Excel::download(
            new MonthlyStatisticsExport($period),
            'monthly-statistics-'.$period->year.'-'.$period->month.'.xlsx'
        );
    }

    public function generalPdf(GeneralStatisticsService $service)
    {
        return Pdf::loadView('pdf.general-statistics', [
            'stages' => $service->get(),
            'totalStudents' => $service->totalStudents(),
        ])->setPaper('a4')->download('general-statistics-'.now()->format('Y-m-d').'.pdf');
    }

    public function generalExcel()
    {
        return Excel::download(new GeneralStatisticsExport(), 'general-statistics-'.now()->format('Y-m-d').'.xlsx');
    }

    private function teacherFilters(Request $request): array
    {
        return [
            'period_id' => $request->integer('period_id') ?: null,
            'teacher_id' => $request->integer('teacher_id') ?: null,
            'subject_id' => $request->integer('subject_id') ?: null,
            'stage_id' => $request->integer('stage_id') ?: null,
            'grade_id' => $request->integer('grade_id') ?: null,
        ];
    }
}
