<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    @include('pdf.reports._styles')
</head>
<body>
    <h1>الإحصائيات الشهرية</h1>
    <p class="meta">الفترة: {{ $period->name }} — تاريخ الطباعة: {{ now()->format('Y-m-d H:i') }}</p>

    @foreach ($groups as $stageName => $rows)
        <h2>{{ $stageName }}</h2>
        <table>
            <thead>
                <tr><th>الصف</th><th>عدد الطلاب</th><th>المطلوب</th><th>الدعم</th><th>الإجمالي</th></tr>
            </thead>
            <tbody>
                @foreach ($rows as $row)
                    <tr>
                        <td>{{ $row->grade_name }}</td>
                        <td>{{ $row->students_count }}</td>
                        <td>{{ number_format($row->parent_amount, 2) }}</td>
                        <td>{{ number_format($row->support_amount, 2) }}</td>
                        <td>{{ number_format($row->total_amount, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td>الإجمالي</td>
                    <td>{{ $rows->sum('students_count') }}</td>
                    <td>{{ number_format($rows->sum('parent_amount'), 2) }}</td>
                    <td>{{ number_format($rows->sum('support_amount'), 2) }}</td>
                    <td>{{ number_format($rows->sum('total_amount'), 2) }}</td>
                </tr>
            </tfoot>
        </table>
    @endforeach
</body>
</html>