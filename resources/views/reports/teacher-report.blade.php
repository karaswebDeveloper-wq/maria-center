<div>
    <h2 class="text-xl font-semibold">تقرير المدرس</h2>

    <div class="mt-6 flex flex-wrap items-end gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">الفترة</label>
            <select wire:model.live="periodId" class="mt-1 rounded-lg border border-gray-300 px-3 py-2">
                <option value="">كل الفترات</option>
                @foreach ($periods as $period)
                    <option value="{{ $period->id }}">{{ $period->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">المدرس</label>
            <select wire:model.live="teacherId" class="mt-1 rounded-lg border border-gray-300 px-3 py-2">
                <option value="">كل المدرسين</option>
                @foreach ($teachers as $teacher)
                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">المادة</label>
            <select wire:model.live="subjectId" class="mt-1 rounded-lg border border-gray-300 px-3 py-2">
                <option value="">كل المواد</option>
                @foreach ($subjects as $subject)
                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">المرحلة</label>
            <select wire:model.live="stageId" class="mt-1 rounded-lg border border-gray-300 px-3 py-2">
                <option value="">كل المراحل</option>
                @foreach ($stages as $stage)
                    <option value="{{ $stage->id }}">{{ $stage->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">الصف</label>
            <select wire:model.live="gradeId" @if (! $stageId) disabled @endif
                    class="mt-1 rounded-lg border border-gray-300 px-3 py-2 disabled:bg-gray-100">
                <option value="">كل الصفوف</option>
                @foreach ($grades as $grade)
                    <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="mt-6 overflow-x-auto rounded-xl border bg-white">
        <table class="w-full text-start text-sm">
            <thead class="border-b bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-4 py-3">الطالب</th>
                    <th class="px-4 py-3">المرحلة</th>
                    <th class="px-4 py-3">الصف</th>
                    <th class="px-4 py-3">المادة</th>
                    <th class="px-4 py-3">المدرس</th>
                    <th class="px-4 py-3">المدة</th>
                    <th class="px-4 py-3">المطلوب من ولي الأمر</th>
                    <th class="px-4 py-3">الدعم</th>
                    <th class="px-4 py-3">الإجمالي</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($rows as $row)
                    <tr>
                        <td class="px-4 py-3">{{ $row->student->name }}</td>
                        <td class="px-4 py-3">{{ $row->student->grade->stage->name }}</td>
                        <td class="px-4 py-3">{{ $row->student->grade->name }}</td>
                        <td class="px-4 py-3">{{ $row->subject->name }}</td>
                        <td class="px-4 py-3">{{ $row->teacher->name }}</td>
                        <td class="px-4 py-3">{{ $row->duration_type->label() }}</td>
                        <td class="px-4 py-3">{{ number_format($row->required_from_parent, 2) }}</td>
                        <td class="px-4 py-3">{{ number_format($row->support_amount, 2) }}</td>
                        <td class="px-4 py-3">{{ number_format($row->fee_amount, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-4 py-6 text-center text-gray-500">لا توجد بيانات مطابقة لهذه الفلاتر.</td>
                    </tr>
                @endforelse
            </tbody>
            @if ($rows->isNotEmpty())
                <tfoot class="border-t bg-gray-50 font-medium">
                    <tr>
                        <td colspan="6" class="px-4 py-3">الإجمالي ({{ $rows->count() }})</td>
                        <td class="px-4 py-3">{{ number_format($rows->sum('required_from_parent'), 2) }}</td>
                        <td class="px-4 py-3">{{ number_format($rows->sum('support_amount'), 2) }}</td>
                        <td class="px-4 py-3">{{ number_format($rows->sum('fee_amount'), 2) }}</td>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>
</div>