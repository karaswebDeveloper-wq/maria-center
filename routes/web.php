<?php

use App\Http\Controllers\AcademicPeriodController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\ReportExportController;
use App\Http\Controllers\StageController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Livewire\Auth\Login;
use App\Livewire\Dashboard;
use App\Livewire\EnrollmentForm;
use App\Livewire\EnrollmentPayments;
use App\Livewire\EnrollmentsTable;
use App\Livewire\PayrollsIndex;
use App\Livewire\PayrollShow;
use App\Livewire\Reports\GeneralStatistics;
use App\Livewire\Reports\MonthlyStatistics;
use App\Livewire\Reports\TeacherPayrollReport;
use App\Livewire\Reports\TeacherReport;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/', Dashboard::class)->name('home');
    Route::post('/logout', LogoutController::class)->name('logout');

    Route::prefix('reports')->name('reports.')->controller(ReportExportController::class)->group(function () {
        Route::get('teacher/export/pdf', 'teacherPdf')->name('teacher.pdf');
        Route::get('teacher/export/excel', 'teacherExcel')->name('teacher.excel');
        Route::get('payroll/export/pdf', 'payrollPdf')->name('payroll.pdf');
        Route::get('payroll/export/excel', 'payrollExcel')->name('payroll.excel');
        Route::get('monthly/export/pdf', 'monthlyPdf')->name('monthly.pdf');
        Route::get('monthly/export/excel', 'monthlyExcel')->name('monthly.excel');
        Route::get('general/export/pdf', 'generalPdf')->name('general.pdf');
        Route::get('general/export/excel', 'generalExcel')->name('general.excel');
    });

    Route::view('/reports', 'reports.index')->name('reports.index');
    Route::get('/reports/teacher', TeacherReport::class)->name('reports.teacher');
    Route::get('/reports/payroll', TeacherPayrollReport::class)->name('reports.payroll');
    Route::get('/reports/monthly', MonthlyStatistics::class)->name('reports.monthly');
    Route::get('/reports/general', GeneralStatistics::class)->name('reports.general');

    Route::get('/payrolls', PayrollsIndex::class)->name('payrolls.index');
    Route::get('/payrolls/{payroll}', PayrollShow::class)->name('payrolls.show');

    Route::get('/enrollments/{enrollment}/payments', EnrollmentPayments::class)->name('enrollments.payments');
    Route::get('/enrollments', EnrollmentsTable::class)->name('enrollments.index');
    Route::get('/enrollments/create', EnrollmentForm::class)->name('enrollments.create');
    Route::get('/enrollments/{enrollment}/edit', EnrollmentForm::class)->name('enrollments.edit');

    Route::resource('stages', StageController::class)->except('show');
    Route::resource('grades', GradeController::class)->except('show');
    Route::resource('subjects', SubjectController::class)->except('show');
    Route::resource('academic-periods', AcademicPeriodController::class)->except('show');
    Route::resource('students', StudentController::class)->except('show');
    Route::resource('families', FamilyController::class)->except('show');
    Route::resource('teachers', TeacherController::class)->except('show');
});
