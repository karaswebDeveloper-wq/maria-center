<div>
    <h2 class="text-xl font-semibold">{{ $enrollment ? 'تعديل الاشتراك' : 'إضافة اشتراك جديد' }}</h2>

    @if (session('success'))
        <div class="mt-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    <form wire:submit="save" class="mt-6 max-w-lg space-y-5">
        <div>
            <label class="block text-sm font-medium text-gray-700">المرحلة الدراسية</label>
            <select wire:model.live="stageId" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2">
                <option value="">اختر المرحلة</option>
                @foreach ($stages as $stage)
                    <option value="{{ $stage->id }}">{{ $stage->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">الصف</label>
            <select wire:model.live="gradeId" @if (! $stageId) disabled @endif
                    class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 disabled:bg-gray-100">
                <option value="">اختر الصف</option>
                @foreach ($grades as $grade)
                    <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">الطالب</label>
            <select wire:model="studentId" @if (! $gradeId) disabled @endif
                    class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 disabled:bg-gray-100">
                <option value="">اختر الطالب</option>
                @foreach ($students as $student)
                    <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->student_code }})</option>
                @endforeach
            </select>
            @error('studentId') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">المادة</label>
            <select wire:model="subjectId" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2">
                <option value="">اختر المادة</option>
                @foreach ($subjects as $subject)
                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                @endforeach
            </select>
            @error('subjectId') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">المدرس</label>
            <select wire:model="teacherId" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2">
                <option value="">اختر المدرس</option>
                @foreach ($teachers as $teacher)
                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                @endforeach
            </select>
            @error('teacherId') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">الفترة الدراسية</label>
            <select wire:model="periodId" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2">
                <option value="">اختر الفترة</option>
                @foreach ($periods as $period)
                    <option value="{{ $period->id }}">{{ $period->name }}</option>
                @endforeach
            </select>
            @error('periodId') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">مدة الاشتراك</label>
            <select wire:model="durationType" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2">
                <option value="">اختر المدة</option>
                @foreach ($durationTypes as $type)
                    <option value="{{ $type->value }}">{{ $type->label() }}</option>
                @endforeach
            </select>
            @error('durationType') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">قيمة الاشتراك</label>
                <input type="number" step="0.01" min="0" wire:model.live="feeAmount"
                       class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2">
                @error('feeAmount') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">قيمة الدعم</label>
                <input type="number" step="0.01" min="0" wire:model.live="supportAmount"
                       class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2">
                @error('supportAmount') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="rounded-lg bg-indigo-50 px-4 py-3 text-sm text-indigo-800">
            المطلوب من ولي الأمر: <span class="font-semibold">{{ number_format(($feeAmount ?? 0) - ($supportAmount ?? 0), 2) }}</span>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">ملاحظات</label>
            <textarea wire:model="notes" rows="3" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2"></textarea>
        </div>

        <button type="submit" wire:loading.attr="disabled"
                class="rounded-lg bg-indigo-600 px-5 py-2 font-medium text-white hover:bg-indigo-700 disabled:opacity-60">
            {{ $enrollment ? 'تحديث' : 'حفظ' }}
        </button>
    </form>
</div>