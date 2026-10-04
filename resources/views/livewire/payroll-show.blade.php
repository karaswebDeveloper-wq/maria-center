<div>
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-semibold">راتب {{ $payroll->academicPeriod->name }}</h2>
            <span @class([
                'mt-1 inline-block rounded-full px-2 py-1 text-xs',
                'bg-gray-100 text-gray-600' => $payroll->status->value === 'draft',
                'bg-amber-100 text-amber-700' => $payroll->status->value === 'approved',
                'bg-green-100 text-green-700' => $payroll->status->value === 'paid',
            ])>
                {{ $payroll->status->label() }}
            </span>
        </div>

        <div class="flex items-center gap-3">
            @if ($payroll->status->value === 'draft')
                <button type="button" wire:click="regenerate" wire:confirm="سيتم إعادة حساب كل العناصر من الاشتراكات الحالية. متابعة؟"
                        class="rounded-lg border px-4 py-2 text-sm hover:bg-gray-50">
                    إعادة التوليد
                </button>
                <button type="button" wire:click="approve" wire:confirm="هل تريد اعتماد هذا الراتب؟ لن يمكن تعديله بعد ذلك."
                        class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                    اعتماد
                </button>
            @elseif ($payroll->status->value === 'approved')
                <button type="button" wire:click="markAsPaid" wire:confirm="هل تريد تسجيل صرف هذا الراتب؟"
                        class="rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700">
                    تسجيل الصرف
                </button>
            @endif
        </div>
    </div>

    @if (session('success'))
        <div class="mt-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="mt-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>
    @endif

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
                @forelse ($items as $item)
                    <tr>
                        <td class="px-4 py-3">{{ $item->teacher->name }}</td>
                        <td class="px-4 py-3">{{ $item->students_count }}</td>
                        <td class="px-4 py-3">{{ number_format($item->fee_amount, 2) }}</td>
                        <td class="px-4 py-3">{{ number_format($item->support_amount, 2) }}</td>
                        <td class="px-4 py-3">{{ number_format($item->gross_amount, 2) }}</td>
                        <td class="px-4 py-3">{{ number_format($item->center_percentage, 2) }}%</td>
                        <td class="px-4 py-3">{{ number_format($item->center_amount, 2) }}</td>
                        <td class="px-4 py-3 font-medium">{{ number_format($item->teacher_amount, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-6 text-center text-gray-500">لا توجد عناصر — لا توجد اشتراكات نشطة لهذه الفترة.</td>
                    </tr>
                @endforelse
            </tbody>
            @if ($items->isNotEmpty())
                <tfoot class="border-t bg-gray-50 font-medium">
                    <tr>
                        <td class="px-4 py-3">الإجمالي</td>
                        <td class="px-4 py-3">{{ $items->sum('students_count') }}</td>
                        <td class="px-4 py-3">{{ number_format($items->sum('fee_amount'), 2) }}</td>
                        <td class="px-4 py-3">{{ number_format($items->sum('support_amount'), 2) }}</td>
                        <td class="px-4 py-3">{{ number_format($items->sum('gross_amount'), 2) }}</td>
                        <td class="px-4 py-3">—</td>
                        <td class="px-4 py-3">{{ number_format($items->sum('center_amount'), 2) }}</td>
                        <td class="px-4 py-3">{{ number_format($items->sum('teacher_amount'), 2) }}</td>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>
</div>