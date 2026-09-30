<?php // GradeController.php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreGradeRequest;
use App\Http\Requests\UpdateGradeRequest;
use App\Models\Grade;
use App\Models\Stage;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class GradeController extends Controller
{
    public function index(): View
    {
        $grades = Grade::query()->with('stage')->orderBy('stage_id')->orderBy('sort_order')->paginate(20);

        return view('grades.index', compact('grades'));
    }

    public function create(): View
    {
        $stages = Stage::query()->active()->orderBy('name')->get();

        return view('grades.create', compact('stages'));
    }

    public function store(StoreGradeRequest $request): RedirectResponse
    {
        Grade::query()->create($request->validated());

        return redirect()->route('grades.index')->with('success', 'تم إضافة الصف بنجاح.');
    }

    public function edit(Grade $grade): View
    {
        $stages = Stage::query()->active()->orderBy('name')->get();

        return view('grades.edit', compact('grade', 'stages'));
    }

    public function update(UpdateGradeRequest $request, Grade $grade): RedirectResponse
    {
        $grade->update($request->validated());

        return redirect()->route('grades.index')->with('success', 'تم تحديث الصف بنجاح.');
    }

    public function destroy(Grade $grade): RedirectResponse
    {
        try {
            $grade->delete();
        } catch (QueryException) {
            return back()->with('error', 'لا يمكن حذف هذا الصف لوجود طلاب مرتبطين به.');
        }

        return redirect()->route('grades.index')->with('success', 'تم حذف الصف بنجاح.');
    }
}