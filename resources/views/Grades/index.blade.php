<x-layouts.app title="الصفوف الدراسية">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold">الصفوف الدراسية</h2>
        <a href="{{ route('grades.create') }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
            إضافة صف
        </a>
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
                    <th class="px-4 py-3">المرحلة</th>
                    <th class="px-4 py-3">الصف</th>
                    <th class="px-4 py-3">الكود</th>
                    <th class="px-4 py-3">الترتيب</th>
                    <th class="px-4 py-3">الحالة</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($grades as $grade)
                    <tr>
                        <td class="px-4 py-3">{{ $grade->stage->name }}</td>
                        <td class="px-4 py-3">{{ $grade->name }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $grade->code }}</td>
                        <td class="px-4 py-3">{{ $grade->sort_order }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2 py-1 text-xs {{ $grade->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ $grade->is_active ? 'نشط' : 'غير نشط' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('grades.edit', $grade) }}" class="text-sm text-indigo-600 hover:underline">تعديل</a>
                                <x-confirm-delete-form :action="route('grades.destroy', $grade)" label="هل تريد حذف هذا الصف؟" />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-500">لا توجد صفوف دراسية بعد.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $grades->links() }}</div>
</x-layouts.app>