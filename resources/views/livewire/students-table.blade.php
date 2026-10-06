<div>
    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="mb-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>
    @endif

    <div class="flex flex-wrap items-end gap-4">
        <div class="min-w-[220px] flex-1">
            <label class="block text-sm font-medium text-gray-700">بحث</label>
            <input type="text" wire:model.live.debounce.400ms="search" placeholder="الاسم، الكود، أو الهاتف"
                   class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2">
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

    <div class="mt-6 overflow-x-auto rounded-xl border bg-white" wire:loading.class="opacity-50">
        <table class="w-full text-start text-sm">
            <thead class="border-b bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-4 py-3"></th>
                    <th class="px-4 py-3">الكود</th>
                    <th class="px-4 py-3">الاسم</th>
                    <th class="px-4 py-3">الأسرة</th>
                    <th class="px-4 py-3">المرحلة / الصف</th>
                    <th class="px-4 py-3">الهاتف</th>
                    <th class="px-4 py-3">الحالة</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($students as $student)
                    <tr wire:key="student-{{ $student->id }}">
                        <td class="px-4 py-3">
                            <img src="{{ $student->photoUrl() }}" alt="{{ $student->name }}" class="h-10 w-10 rounded-full object-cover">
                        </td>
                        <td class="px-4 py-3 text-gray-500" dir="ltr">{{ $student->student_code }}</td>
                        <td class="px-4 py-3">{{ $student->name }}</td>
                        <td class="px-4 py-3">{{ $student->family?->name ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $student->grade->stage->name }} / {{ $student->grade->name }}</td>
                        <td class="px-4 py-3 text-gray-500" dir="ltr">{{ $student->phone ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2 py-1 text-xs {{ $student->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ $student->is_active ? 'نشط' : 'غير نشط' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('students.edit', $student) }}" class="text-sm text-indigo-600 hover:underline">تعديل</a>
                                <button type="button" wire:click="delete({{ $student->id }})"
                                        wire:confirm="هل تريد حذف هذا الطالب؟"
                                        class="text-sm text-red-600 hover:underline">
                                    حذف
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-6 text-center text-gray-500">لا يوجد طلاب مطابقون لهذا البحث.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $students->links() }}</div>
</div>
