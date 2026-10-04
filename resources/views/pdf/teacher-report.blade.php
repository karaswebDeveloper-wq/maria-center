<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    @include('pdf.reports._styles')
</head>
<body>
    <h1>تقرير المدرس</h1>
    <p class="meta">تاريخ الطباعة: {{ now()->format('Y-m-d H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>الطالب</th><th>المرحلة</th><th>الصف</th><th>المادة</th>
                <th>المدرس</th><th>المدة</th><th>المطلوب</th><th>الدعم</th><th>الإجمالي</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $row)
                <tr>
                    <td>{{ $row->student->name }}</td>
                    <td>{{ $row->student->grade->stage->name }}</td>
                    <td>{{ $row->student->grade->name }}</td>
                    <td>{{ $row->subject->name }}</td>
                    <td>{{ $row->teacher->name }}</td>
                    <td>{{ $row->duration_type->label() }}</td>
                    <td>{{ number_format($row->required_from_parent, 2) }}</td>
                    <td>{{ number_format($row->support_amount, 2) }}</td>
                    <td>{{ number_format($row->fee_amount, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="9">لا توجد بيانات.</td></tr>
            @endforelse
        </tbody>
        @if ($rows->isNotEmpty())
            <tfoot>
                <tr>
                    <td colspan="6">الإجمالي ({{ $rows->count() }})</td>
                    <td>{{ number_format($rows->sum('required_from_parent'), 2) }}</td>
                    <td>{{ number_format($rows->sum('support_amount'), 2) }}</td>
                    <td>{{ number_format($rows->sum('fee_amount'), 2) }}</td>
                </tr>
            </tfoot>
        @endif
    </table>
</body>
</html>