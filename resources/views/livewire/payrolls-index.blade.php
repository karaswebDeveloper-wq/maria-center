<div>
    <h2 class="text-xl font-semibold">رواتب المدرسين</h2>

    <form wire:submit="generate" class="mt-6 flex flex-wrap items-end gap-3 rounded-xl border bg-white p-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">الفترة الدراسية</label>
            <select wire:model="periodId" class="mt-1 rounded-lg border border-gray-300 px-3 py-2">
                <option value="">اختر الفترة</option>
                @foreach ($periods as $period)
                    <option value="{{ $period->id }}">{{ $period->name }}</option>
                @endforeach
            </select>
            @error('periodId') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <button type="submit" wire:loading.attr="disabled"
                class="rounded-lg bg-indigo-600 px-5 py-2 font-medium text-white hover:bg-indigo-700 disabled:opacity-60">
            توليد / فتح الراتب
        </button>
    </form>

    <div class="mt-6 overflow-x-auto rounded-xl border bg-white">
        <table class="w-full text-start text-sm">
            <thead class="border-b bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-4 py-3">الفترة</th>
                    <th class="px-4 py-3">عدد المدرسين</th>
                    <th class="px-4 py-3">الحالة</th>
                    <th class="px-4 py-3">تاريخ التوليد</th>
                    <th class="px-4 py-3">بواسطة</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($payrolls as $payroll)
                    <tr>
                        <td class="px-4 py-3">{{ $payroll->academicPeriod->name }}</td>
                        <td class="px-4 py-3">{{ $payroll->items_count }}</td>
                        <td class="px-4 py-3">
                            <span @class([
                                'rounded-full px-2 py-1 text-xs',
                                'bg-gray-100 text-gray-600' => $payroll->status->value === 'draft',
                                'bg-amber-100 text-amber-700' => $payroll->status->value === 'approved',
                                'bg-green-100 text-green-700' => $payroll->status->value === 'paid',
                            ])>
                                {{ $payroll->status->label() }}
                            </span>
                        </td>
                        <td class="px-4 py-3">{{ $payroll->generated_at?->format('Y-m-d H:i') ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $payroll->creator->name }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('payrolls.show', $payroll) }}" class="text-sm text-indigo-600 hover:underline">عرض</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-500">لا توجد رواتب مولّدة بعد.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $payrolls->links() }}</div>
</div>