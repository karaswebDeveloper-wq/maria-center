<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AcademicPeriodController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\StageController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\StudentController;
use App\Livewire\EnrollmentForm;

use App\Livewire\EnrollmentsTable;

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