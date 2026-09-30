<x-layouts.app title="الفترات الدراسية">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold">الفترات الدراسية</h2>
        <a href="{{ route('academic-periods.create') }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
            إضافة فترة
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
                    <th class="px-4 py-3">السنة</th>
                    <th class="px-4 py-3">الشهر</th>
                    <th class="px-4 py-3">من</th>
                    <th class="px-4 py-3">إلى</th>
                    <th class="px-4 py-3">الحالة</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($academicPeriods as $period)
                    <tr>
                        <td class="px-4 py-3">{{ $period->name }}</td>
                        <td class="px-4 py-3">{{ $period->year }}</td>
                        <td class="px-4 py-3">{{ $period->month }}</td>
                        <td class="px-4 py-3">{{ $period->starts_at->format('Y-m-d') }}</td>
                        <td class="px-4 py-3">{{ $period->ends_at->format('Y-m-d') }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2 py-1 text-xs {{ $period->is_closed ? 'bg-gray-100 text-gray-600' : 'bg-green-100 text-green-700' }}">
                                {{ $period->is_closed ? 'مغلقة' : 'مفتوحة' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('academic-periods.edit', $period) }}" class="text-sm text-indigo-600 hover:underline">تعديل</a>
                                <x-confirm-delete-form :action="route('academic-periods.destroy', $period)" label="هل تريد حذف هذه الفترة؟" />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-gray-500">لا توجد فترات دراسية بعد.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $academicPeriods->links() }}</div>
</x-layouts.app>