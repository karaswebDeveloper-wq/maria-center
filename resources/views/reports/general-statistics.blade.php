<div>
    <h2 class="text-xl font-semibold">الإحصائيات العامة</h2>

    <div class="mt-6 rounded-xl border bg-white p-4">
        <p class="text-gray-500">إجمالي الطلاب النشطين</p>
        <p class="text-3xl font-semibold">{{ $totalStudents }}</p>
    </div>

    <div class="mt-6 overflow-x-auto rounded-xl border bg-white">
        <table class="w-full text-start text-sm">
            <thead class="border-b bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-4 py-3">المرحلة</th>
                    <th class="px-4 py-3">الصف</th>
                    <th class="px-4 py-3">عدد الطلاب</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($stages as $stage)
                    @forelse ($stage->grades as $grade)
                        <tr>
                            <td class="px-4 py-3">{{ $loop->first ? $stage->name : '' }}</td>
                            <td class="px-4 py-3">{{ $grade->name }}</td>
                            <td class="px-4 py-3">{{ $grade->students_count }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-3">{{ $stage->name }}</td>
                            <td class="px-4 py-3 text-gray-400">لا توجد صفوف</td>
                            <td class="px-4 py-3">0</td>
                        </tr>
                    @endforelse
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-6 text-center text-gray-500">لا توجد مراحل دراسية.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>