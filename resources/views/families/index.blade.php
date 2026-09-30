<x-layouts.app title="الأسر">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold">الأسر</h2>
        <a href="{{ route('families.create') }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
            إضافة أسرة
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
                    <th class="px-4 py-3">الاسم</th>
                    <th class="px-4 py-3">الهاتف</th>
                    <th class="px-4 py-3">العنوان</th>
                    <th class="px-4 py-3">عدد الطلاب</th>
                    <th class="px-4 py-3">الحالة</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($families as $family)
                    <tr>
                        <td class="px-4 py-3">{{ $family->name }}</td>
                        <td class="px-4 py-3 text-gray-500" dir="ltr">{{ $family->phone ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $family->address ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $family->students_count }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2 py-1 text-xs {{ $family->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ $family->is_active ? 'نشط' : 'غير نشط' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('families.edit', $family) }}" class="text-sm text-indigo-600 hover:underline">تعديل</a>
                                <x-confirm-delete-form :action="route('families.destroy', $family)" label="هل تريد حذف هذه الأسرة؟" />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-500">لا توجد أسر بعد.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $families->links() }}</div>
</x-layouts.app>