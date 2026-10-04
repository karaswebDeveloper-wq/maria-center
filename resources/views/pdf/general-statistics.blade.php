<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    @include('pdf.reports._styles')
</head>
<body>
    <h1>الإحصائيات العامة</h1>
    <p class="meta">إجمالي الطلاب النشطين: {{ $totalStudents }} — تاريخ الطباعة: {{ now()->format('Y-m-d H:i') }}</p>

    <table>
        <thead>
            <tr><th>المرحلة</th><th>الصف</th><th>عدد الطلاب</th></tr>
        </thead>
        <tbody>
            @forelse ($stages as $stage)
                @forelse ($stage->grades as $grade)
                    <tr>
                        <td>{{ $loop->first ? $stage->name : '' }}</td>
                        <td>{{ $grade->name }}</td>
                        <td>{{ $grade->students_count }}</td>
                    </tr>
                @empty
                    <tr><td>{{ $stage->name }}</td><td>—</td><td>0</td></tr>
                @endforelse
            @empty
                <tr><td colspan="3">لا توجد بيانات.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>