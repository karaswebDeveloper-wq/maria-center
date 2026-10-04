<div>
    <h2 class="text-xl font-semibold">مدفوعات الاشتراك</h2>

    <div class="mt-4 rounded-xl border bg-white p-4 text-sm">
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
            <div><span class="text-gray-500">الطالب:</span> {{ $enrollment->student->name }}</div>
            <div><span class="text-gray-500">المادة:</span> {{ $enrollment->subject->name }}</div>
            <div><span class="text-gray-500">المدرس:</span> {{ $enrollment->teacher->name }}</div>
            <div><span class="text-gray-500">الفترة:</span> {{ $enrollment->academicPeriod->name }}</div>
        </div>

        <div class="mt-4 grid grid-cols-3 gap-3 border-t pt-4">
            <div>
                <p class="text-gray-500">المطلوب من ولي الأمر</p>
                <p class="text-lg font-semibold">{{ number_format($enrollment->required_from_parent, 2) }}</p>
            </div>
            <div>
                <p class="text-gray-500">المدفوع</p>
                <p class="text-lg font-semibold text-green-700">{{ number_format($enrollment->paid_amount, 2) }}</p>
            </div>
            <div>
                <p class="text-gray-500">المتبقي</p>
                <p class="text-lg font-semibold {{ $enrollment->remaining_amount > 0 ? 'text-red-600' : 'text-green-700' }}">
                    {{ number_format($enrollment->remaining_amount, 2) }}
                </p>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="mt-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    @if ($enrollment->remaining_amount > 0)
        <form wire:submit="save" class="mt-6 max-w-lg space-y-5 rounded-xl border bg-white p-4">
            <h3 class="font-medium">تسجيل دفعة جديدة</h3>

            <div>
                <label class="block text-sm font-medium text-gray-700">القيمة</label>
                <input type="number" step="0.01" min="0.01" wire:model="amount"
                       class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2">
                @error('amount') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">تاريخ الدفع</label>
                <input type="date" wire:model="paymentDate" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2">
                @error('paymentDate') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">طريقة الدفع</label>
                <select wire:model="paymentMethod" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2">
                    <option value="">اختر طريقة الدفع</option>
                    @foreach ($paymentMethods as $method)
                        <option value="{{ $method->value }}">{{ $method->label() }}</option>
                    @endforeach
                </select>
                @error('paymentMethod') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">رقم مرجعي (اختياري)</label>
                <input type="text" dir="ltr" wire:model="reference" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">ملاحظات</label>
                <textarea wire:model="notes" rows="2" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2"></textarea>
            </div>

            <button type="submit" wire:loading.attr="disabled"
                    class="rounded-lg bg-indigo-600 px-5 py-2 font-medium text-white hover:bg-indigo-700 disabled:opacity-60">
                تسجيل الدفعة
            </button>
        </form>
    @else
        <div class="mt-6 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">
            تم سداد كامل المبلغ المطلوب لهذا الاشتراك.
        </div>
    @endif

    <div class="mt-6 overflow-x-auto rounded-xl border bg-white">
        <table class="w-full text-start text-sm">
            <thead class="border-b bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-4 py-3">التاريخ</th>
                    <th class="px-4 py-3">القيمة</th>
                    <th class="px-4 py-3">الطريقة</th>
                    <th class="px-4 py-3">المرجع</th>
                    <th class="px-4 py-3">استلمها</th>
                    <th class="px-4 py-3">ملاحظات</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($payments as $payment)
                    <tr>
                        <td class="px-4 py-3">{{ $payment->payment_date->format('Y-m-d') }}</td>
                        <td class="px-4 py-3">{{ number_format($payment->amount, 2) }}</td>
                        <td class="px-4 py-3">{{ $payment->payment_method->label() }}</td>
                        <td class="px-4 py-3 text-gray-500" dir="ltr">{{ $payment->reference ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $payment->receivedBy->name }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $payment->notes ?? '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-500">لا توجد دفعات مسجلة بعد.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>