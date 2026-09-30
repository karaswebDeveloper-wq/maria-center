<div>
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold">الاشتراكات</h2>
        <a href="{{ route('enrollments.create') }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
            إضافة اشتراك
        </a>
    </div>

    @if (session('success'))
        <div class="mt-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="mt-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>
    @endif

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

        <div>
            <label class="block text-sm font-medium text-gray-700">الحالة</label>
            <select wire:model.live="status" class="mt-1 rounded-lg border border-gray-300 px-3 py-2">
                <option value="active">نشط</option>
                <option value="cancelled">ملغي</option>
                <option value="all">الكل</option>
            </select>
        </div>
    </div>

    <div class="mt-6 overflow-x-auto rounded-xl border bg-white" wire:loading.class="opacity-50">
        <table class="w-full text-start text-sm">
            <thead class="border-b bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-4 py-3">الطالب</th>
                    <th class="px-4 py-3">المرحلة / الصف</th>
                    <th class="px-4 py-3">المادة</th>
                    <th class="px-4 py-3">المدرس</th>
                    <th class="px-4 py-3">الفترة</th>
                    <th class="px-4 py-3">المدة</th>
                    <th class="px-4 py-3">المطلوب</th>
                    <th class="px-4 py-3">المدفوع</th>
                    <th class="px-4 py-3">المتبقي</th>
                    <th class="px-4 py-3">الحالة</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($enrollments as $enrollment)
                    @php
                        $paid = (float) ($enrollment->payments_sum_amount ?? 0);
                        $remaining = $enrollment->required_from_parent - $paid;
                    @endphp
                    <tr wire:key="enrollment-{{ $enrollment->id }}">
                        <td class="px-4 py-3">{{ $enrollment->student->name }}</td>
                        <td class="px-4 py-3">{{ $enrollment->student->grade->stage->name }} / {{ $enrollment->student->grade->name }}</td>
                        <td class="px-4 py-3">{{ $enrollment->subject->name }}</td>
                        <td class="px-4 py-3">{{ $enrollment->teacher->name }}</td>
                        <td class="px-4 py-3">{{ $enrollment->academicPeriod->name }}</td>
                        <td class="px-4 py-3">{{ $enrollment->duration_type->label() }}</td>
                        <td class="px-4 py-3">{{ number_format($enrollment->required_from_parent, 2) }}</td>
                        <td class="px-4 py-3">{{ number_format($paid, 2) }}</td>
                        <td class="px-4 py-3 {{ $remaining > 0 ? 'text-red-600' : 'text-green-700' }}">
                            {{ number_format($remaining, 2) }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2 py-1 text-xs {{ $enrollment->status->value === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ $enrollment->status->label() }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('enrollments.edit', $enrollment) }}" class="text-sm text-indigo-600 hover:underline">تعديل</a>

                                @if ($enrollment->status->value === 'active')
                                    <button type="button" wire:click="cancel({{ $enrollment->id }})"
                                            wire:confirm="هل تريد إلغاء هذا الاشتراك؟"
                                            class="text-sm text-red-600 hover:underline">
                                        إلغاء
                                    </button>
                                @else
                                    <button type="button" wire:click="reactivate({{ $enrollment->id }})"
                                            wire:confirm="هل تريد إعادة تفعيل هذا الاشتراك؟"
                                            class="text-sm text-green-700 hover:underline">
                                        تفعيل
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="px-4 py-6 text-center text-gray-500">لا توجد اشتراكات مطابقة.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $enrollments->links() }}</div>
</div>


