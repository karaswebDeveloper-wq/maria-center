<div>
    <h2 class="text-xl font-semibold">الإحصائيات الشهرية</h2>

    <div class="mt-6">
        <label class="block text-sm font-medium text-gray-700">الفترة الدراسية</label>
        <select wire:model.live="periodId" class="mt-1 rounded-lg border border-gray-300 px-3 py-2">
            <option value="">اختر الفترة</option>
            @foreach ($periods as $period)
                <option value="{{ $period->id }}">{{ $period->name }}</option>
            @endforeach
        </select>
    </div>

    @if ($periodId && $groups->isEmpty())
        <div class="mt-6 rounded-lg bg-gray-50 px-4 py-3 text-sm text-gray-500">لا توجد اشتراكات نشطة لهذه الفترة.</div>
    @endif

    @foreach ($groups as $stageName => $rows)
        <div class="mt-6 overflow-x-auto rounded-xl border bg-white">
            <div class="border-b bg-gray-50 px-4 py-2 font-medium">{{ $stageName }}</div>
            <table class="w-full text-start text-sm">
                <thead class="border-b text-gray-600">
                    <tr>
                        <th class="px-4 py-3">الصف</th>
                        <th class="px-4 py-3">عدد الطلاب</th>
                        <th class="px-4 py-3">المطلوب من أولياء الأمور</th>
                        <th class="px-4 py-3">الدعم</th>
                        <th class="px-4 py-3">الإجمالي</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach ($rows as $row)
                        <tr>
                            <td class="px-4 py-3">{{ $row->grade_name }}</td>
                            <td class="px-4 py-3">{{ $row->students_count }}</td>
                            <td class="px-4 py-3">{{ number_format($row->parent_amount, 2) }}</td>
                            <td class="px-4 py-3">{{ number_format($row->support_amount, 2) }}</td>
                            <td class="px-4 py-3">{{ number_format($row->total_amount, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="border-t bg-gray-50 font-medium">
                    <tr>
                        <td class="px-4 py-3">إجمالي {{ $stageName }}</td>
                        <td class="px-4 py-3">{{ $rows->sum('students_count') }}</td>
                        <td class="px-4 py-3">{{ number_format($rows->sum('parent_amount'), 2) }}</td>
                        <td class="px-4 py-3">{{ number_format($rows->sum('support_amount'), 2) }}</td>
                        <td class="px-4 py-3">{{ number_format($rows->sum('total_amount'), 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @endforeach
</div>