<?php // AcademicPeriodController.php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreAcademicPeriodRequest;
use App\Http\Requests\UpdateAcademicPeriodRequest;
use App\Models\AcademicPeriod;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AcademicPeriodController extends Controller
{
    public function index(): View
    {
        $academicPeriods = AcademicPeriod::query()->orderByDesc('year')->orderByDesc('month')->paginate(20);

        return view('academic-periods.index', compact('academicPeriods'));
    }

    public function create(): View
    {
        return view('academic-periods.create');
    }

    public function store(StoreAcademicPeriodRequest $request): RedirectResponse
    {
        AcademicPeriod::query()->create($request->validated());

        return redirect()->route('academic-periods.index')->with('success', 'تم إضافة الفترة الدراسية بنجاح.');
    }

    public function edit(AcademicPeriod $academicPeriod): View
    {
        return view('academic-periods.edit', compact('academicPeriod'));
    }

    public function update(UpdateAcademicPeriodRequest $request, AcademicPeriod $academicPeriod): RedirectResponse
    {
        $academicPeriod->update($request->validated());

        return redirect()->route('academic-periods.index')->with('success', 'تم تحديث الفترة الدراسية بنجاح.');
    }

    public function destroy(AcademicPeriod $academicPeriod): RedirectResponse
    {
        try {
            $academicPeriod->delete();
        } catch (QueryException) {
            return back()->with('error', 'لا يمكن حذف هذه الفترة لوجود اشتراكات أو رواتب مرتبطة بها.');
        }

        return redirect()->route('academic-periods.index')->with('success', 'تم حذف الفترة الدراسية بنجاح.');
    }
}