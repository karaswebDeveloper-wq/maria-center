<div>
    <h2 class="text-xl font-semibold">التقرير المالي للمدرسين</h2>
    <p class="mt-1 text-sm text-gray-500">تقرير للمراجعة فقط — لا يُنشئ أو يعتمد أي راتب.</p>

    <div class="mt-6">
        <label class="block text-sm font-medium text-gray-700">الفترة الدراسية</label>
        <select wire:model.live="periodId" class="mt-1 rounded-lg border border-gray-300 px-3 py-2">
            <option value="">اختر الفترة</option>
            @foreach ($periods as $period)
                <option value="{{ $period->id }}">{{ $period->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mt-6 overflow-x-auto rounded-xl border bg-white">
        <table class="w-full text-start text-sm">
            <thead class="border-b bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-4 py-3">المدرس</th>
                    <th class="px-4 py-3">عدد الطلاب</th>
                    <th class="px-4 py-3">المبلغ المستحق</th>
                    <th class="px-4 py-3">الدعم</th>
                    <th class="px-4 py-3">الإجمالي</th>
                    <th class="px-4 py-3">نسبة المركز</th>
                    <th class="px-4 py-3">نصيب المركز</th>
                    <th class="px-4 py-3">نصيب المدرس</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($rows as $row)
                    <tr>
                        <td class="px-4 py-3">{{ $row->teacher->name }}</td>
                        <td class="px-4 py-3">{{ $row->students_count }}</td>
                        <td class="px-4 py-3">{{ number_format($row->fee_amount, 2) }}</td>
                        <td class="px-4 py-3">{{ number_format($row->support_amount, 2) }}</td>
                        <td class="px-4 py-3">{{ number_format($row->gross_amount, 2) }}</td>
                        <td class="px-4 py-3">{{ number_format($row->center_percentage, 2) }}%</td>
                        <td class="px-4 py-3">{{ number_format($row->center_amount, 2) }}</td>
                        <td class="px-4 py-3 font-medium">{{ number_format($row->teacher_amount, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-6 text-center text-gray-500">
                            {{ $periodId ? 'لا توجد اشتراكات نشطة لهذه الفترة.' : 'اختر فترة لعرض التقرير.' }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>