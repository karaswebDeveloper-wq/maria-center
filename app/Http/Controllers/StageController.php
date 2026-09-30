<?php // StageController.php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreStageRequest;
use App\Http\Requests\UpdateStageRequest;
use App\Models\Stage;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StageController extends Controller
{
    public function index(): View
    {
        $stages = Stage::query()->withCount('grades')->orderBy('name')->paginate(20);

        return view('stages.index', compact('stages'));
    }

    public function create(): View
    {
        return view('stages.create');
    }

    public function store(StoreStageRequest $request): RedirectResponse
    {
        Stage::query()->create($request->validated());

        return redirect()->route('stages.index')->with('success', 'تم إضافة المرحلة بنجاح.');
    }

    public function edit(Stage $stage): View
    {
        return view('stages.edit', compact('stage'));
    }

    public function update(UpdateStageRequest $request, Stage $stage): RedirectResponse
    {
        $stage->update($request->validated());

        return redirect()->route('stages.index')->with('success', 'تم تحديث المرحلة بنجاح.');
    }

    public function destroy(Stage $stage): RedirectResponse
    {
        try {
            $stage->delete();
        } catch (QueryException) {
            return back()->with('error', 'لا يمكن حذف هذه المرحلة لوجود صفوف دراسية مرتبطة بها.');
        }

        return redirect()->route('stages.index')->with('success', 'تم حذف المرحلة بنجاح.');
    }
}