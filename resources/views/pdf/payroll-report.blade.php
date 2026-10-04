<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    @include('pdf.reports._styles')
</head>
<body>
    <h1>التقرير المالي للمدرسين</h1>
    <p class="meta">الفترة: {{ $period->name }} — تاريخ الطباعة: {{ now()->format('Y-m-d H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>المدرس</th><th>عدد الطلاب</th><th>المستحق</th><th>الدعم</th>
                <th>الإجمالي</th><th>النسبة</th><th>نصيب المركز</th><th>نصيب المدرس</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $row)
                <tr>
                    <td>{{ $row->teacher->name }}</td>
                    <td>{{ $row->students_count }}</td>
                    <td>{{ number_format($row->fee_amount, 2) }}</td>
                    <td>{{ number_format($row->support_amount, 2) }}</td>
                    <td>{{ number_format($row->gross_amount, 2) }}</td>
                    <td>{{ number_format($row->center_percentage, 2) }}%</td>
                    <td>{{ number_format($row->center_amount, 2) }}</td>
                    <td>{{ number_format($row->teacher_amount, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="8">لا توجد بيانات.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>