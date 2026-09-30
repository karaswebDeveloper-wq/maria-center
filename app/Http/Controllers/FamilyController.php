<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreFamilyRequest;
use App\Http\Requests\UpdateFamilyRequest;
use App\Models\Family;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FamilyController extends Controller
{
    public function index(): View
    {
        $families = Family::query()->withCount('students')->orderBy('name')->paginate(20);

        return view('families.index', compact('families'));
    }

    public function create(): View
    {
        return view('families.create');
    }

    public function store(StoreFamilyRequest $request): RedirectResponse
    {
        Family::query()->create($request->validated());

        return redirect()->route('families.index')->with('success', 'تم إضافة الأسرة بنجاح.');
    }

    public function edit(Family $family): View
    {
        return view('families.edit', compact('family'));
    }

    public function update(UpdateFamilyRequest $request, Family $family): RedirectResponse
    {
        $family->update($request->validated());

        return redirect()->route('families.index')->with('success', 'تم تحديث بيانات الأسرة بنجاح.');
    }

    public function destroy(Family $family): RedirectResponse
    {
        try {
            $family->delete(); // soft delete
        } catch (QueryException) {
            return back()->with('error', 'لا يمكن حذف هذه الأسرة لوجود طلاب مرتبطين بها.');
        }

        return redirect()->route('families.index')->with('success', 'تم حذف الأسرة بنجاح.');
    }
}