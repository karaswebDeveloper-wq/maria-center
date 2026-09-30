<?php // SubjectController.php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubjectRequest;
use App\Http\Requests\UpdateSubjectRequest;
use App\Models\Subject;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SubjectController extends Controller
{
    public function index(): View
    {
        $subjects = Subject::query()->orderBy('name')->paginate(20);

        return view('subjects.index', compact('subjects'));
    }

    public function create(): View
    {
        return view('subjects.create');
    }

    public function store(StoreSubjectRequest $request): RedirectResponse
    {
        Subject::query()->create($request->validated());

        return redirect()->route('subjects.index')->with('success', 'تم إضافة المادة بنجاح.');
    }

    public function edit(Subject $subject): View
    {
        return view('subjects.edit', compact('subject'));
    }

    public function update(UpdateSubjectRequest $request, Subject $subject): RedirectResponse
    {
        $subject->update($request->validated());

        return redirect()->route('subjects.index')->with('success', 'تم تحديث المادة بنجاح.');
    }

    public function destroy(Subject $subject): RedirectResponse
    {
        try {
            $subject->delete(); // soft delete
        } catch (QueryException) {
            return back()->with('error', 'لا يمكن حذف هذه المادة لوجود اشتراكات مرتبطة بها.');
        }

        return redirect()->route('subjects.index')->with('success', 'تم حذف المادة بنجاح.');
    }
}