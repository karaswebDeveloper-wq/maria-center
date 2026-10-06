<x-layouts.app title="المدرسون">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold">المدرسون</h2>
        <a href="{{ route('teachers.create') }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
            إضافة مدرس
        </a>
    </div>

    @if (session('success'))
        <div class="mt-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="mt-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>
    @endif

    <form method="GET" action="{{ route('teachers.index') }}" class="mt-6 flex flex-wrap items-end gap-4">
        <div class="min-w-[220px] flex-1">
            <label for="teacher-search" class="block text-sm font-medium text-gray-700">بحث</label>
            <input id="teacher-search" type="text" name="search" value="{{ $search }}"
                   placeholder="الاسم، الهاتف، أو البريد الإلكتروني"
                   class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-right">
        </div>
        <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">بحث</button>
    </form>

    <div class="mt-6 overflow-x-auto rounded-xl border bg-white">
        <table class="w-full text-start text-sm">
            <thead class="border-b bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-4 py-3"></th>
                    <th class="px-4 py-3">الاسم</th>
                    <th class="px-4 py-3">الهاتف</th>
                    <th class="px-4 py-3">البريد الإلكتروني</th>
                    <th class="px-4 py-3">نسبة المركز</th>
                    <th class="px-4 py-3">الحالة</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($teachers as $teacher)
                    <tr>
                        <td class="px-4 py-3">
                            <img src="{{ $teacher->photoUrl() }}" alt="{{ $teacher->name }}"
                                 class="h-10 w-10 rounded-full object-cover">
                        </td>
                        <td class="px-4 py-3">{{ $teacher->name }}</td>
                        <td class="px-4 py-3 text-gray-500" dir="ltr">{{ $teacher->phone ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-500" dir="ltr">{{ $teacher->email ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $teacher->percentage }}%</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2 py-1 text-xs {{ $teacher->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ $teacher->is_active ? 'نشط' : 'غير نشط' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('teachers.edit', $teacher) }}" class="text-sm text-indigo-600 hover:underline">تعديل</a>
                                <x-confirm-delete-form :action="route('teachers.destroy', $teacher)" label="هل تريد حذف هذا المدرس؟" />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-gray-500">لا يوجد مدرسون بعد.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $teachers->links() }}</div>
</x-layouts.app>
