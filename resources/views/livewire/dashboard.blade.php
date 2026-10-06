<div class="space-y-8">
    <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm font-medium text-muted">نظرة عامة</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">لوحة التحكم</h1>
            <p class="mt-2 text-sm text-secondary">ملخص النشاط والإيرادات في المركز</p>
        </div>

        <div class="w-full sm:w-64">
            <label for="dashboard-period" class="mb-1.5 block text-sm font-medium text-secondary">الفترة الدراسية</label>
            <select
                id="dashboard-period"
                wire:model.live="periodId"
                class="w-full rounded-xl border border-secondary/20 bg-surface px-3.5 py-2.5 text-sm text-gray-800 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/25"
            >
                @forelse ($periods as $p)
                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                @empty
                    <option value="">لا توجد فترات دراسية</option>
                @endforelse
            </select>
        </div>
    </div>

    @if (! $period)
        <div class="rounded-2xl border border-primary/20 bg-surface px-5 py-4 text-sm leading-6 text-secondary">
            لا توجد فترات دراسية بعد. أضف فترة من صفحة الفترات الدراسية لعرض الإحصائيات.
        </div>
    @else
        {{-- Stat cards --}}
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            <div class="rounded-2xl border border-secondary/15 bg-surface p-5">
                <p class="text-sm font-medium text-muted">إجمالي الطلاب</p>
                <p class="mt-3 text-3xl font-bold tracking-tight text-primary">{{ number_format($cards['total_students']) }}</p>
            </div>
            <div class="rounded-2xl border border-secondary/15 bg-surface p-5">
                <p class="text-sm font-medium text-muted">المدرسون النشطون</p>
                <p class="mt-3 text-3xl font-bold tracking-tight text-gray-800">{{ number_format($cards['active_teachers']) }}</p>
            </div>
            <div class="rounded-2xl border border-secondary/15 bg-surface p-5">
                <p class="text-sm font-medium text-muted">إيرادات الفترة</p>
                <p class="mt-3 text-3xl font-bold tracking-tight text-primary">{{ number_format($cards['monthly_revenue'], 2) }}</p>
            </div>
            <div class="rounded-2xl border border-secondary/15 bg-surface p-5">
                <p class="text-sm font-medium text-muted">إجمالي الدعم</p>
                <p class="mt-3 text-3xl font-bold tracking-tight text-gray-800">{{ number_format($cards['total_support'], 2) }}</p>
            </div>
            <div class="rounded-2xl border border-secondary/15 bg-surface p-5">
                <p class="text-sm font-medium text-muted">مستحقات المدرسين</p>
                <p class="mt-3 text-3xl font-bold tracking-tight text-gray-800">{{ number_format($cards['teacher_entitlements'], 2) }}</p>
            </div>
            <div class="rounded-2xl border border-secondary/15 bg-surface p-5">
                <p class="text-sm font-medium text-muted">مبالغ متبقية على أولياء الأمور</p>
                <p class="mt-3 text-3xl font-bold tracking-tight {{ $cards['outstanding_payments'] > 0 ? 'text-amber-800' : 'text-primary' }}">
                    {{ number_format($cards['outstanding_payments'], 2) }}
                </p>
            </div>
        </div>

        {{-- Charts --}}
        <div class="grid gap-4 lg:grid-cols-2">
            <div wire:key="stage-chart-{{ $periodId }}" x-data x-init="
                initChart('stageChart', 'bar',
                    @js($stagesBreakdown->pluck('name')),
                    @js($stagesBreakdown->map(fn ($s) => $s->grades->sum('students_count'))),
                    'عدد الطلاب')
            " class="rounded-2xl border border-secondary/15 bg-surface p-5 sm:p-6">
                <p class="mb-4 text-sm font-semibold text-secondary">الطلاب حسب المرحلة</p>
                <canvas id="stageChart" height="180"></canvas>
            </div>

            <div wire:key="grade-chart-{{ $periodId }}" x-data x-init="
                initChart('gradeChart', 'bar',
                    @js($stagesBreakdown->flatMap(fn ($s) => $s->grades->map(fn ($g) => $s->name.' / '.$g->name))),
                    @js($stagesBreakdown->flatMap(fn ($s) => $s->grades->pluck('students_count'))),
                    'عدد الطلاب')
            " class="rounded-2xl border border-secondary/15 bg-surface p-5 sm:p-6">
                <p class="mb-4 text-sm font-semibold text-secondary">الطلاب حسب الصف</p>
                <canvas id="gradeChart" height="180"></canvas>
            </div>

            <div wire:key="revenue-chart-{{ $periodId }}" x-data x-init="
                initChart('revenueChart', 'line', @js($trend->pluck('label')), @js($trend->pluck('revenue')), 'الإيرادات')
            " class="rounded-2xl border border-secondary/15 bg-surface p-5 sm:p-6">
                <p class="mb-4 text-sm font-semibold text-secondary">الإيرادات الشهرية (آخر 6 فترات)</p>
                <canvas id="revenueChart" height="180"></canvas>
            </div>

            <div wire:key="enrollment-chart-{{ $periodId }}" x-data x-init="
                initChart('enrollmentChart', 'bar', @js($trend->pluck('label')), @js($trend->pluck('enrollments_count')), 'عدد الاشتراكات')
            " class="rounded-2xl border border-secondary/15 bg-surface p-5 sm:p-6">
                <p class="mb-4 text-sm font-semibold text-secondary">الاشتراكات الشهرية (آخر 6 فترات)</p>
                <canvas id="enrollmentChart" height="180"></canvas>
            </div>
        </div>
    @endif

    {{-- Quick actions --}}
    <section aria-labelledby="dashboard-actions-title">
        <h2 id="dashboard-actions-title" class="mb-3 text-sm font-semibold text-secondary">إجراءات سريعة</h2>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('students.create') }}" class="inline-flex min-h-11 w-full flex-none items-center justify-center whitespace-nowrap rounded-xl bg-primary px-4 py-2.5 text-center text-sm font-semibold leading-5 text-white transition hover:bg-primary-hover focus:outline-none focus:ring-2 focus:ring-primary/40 focus:ring-offset-2 sm:w-auto">إضافة طالب</a>
            <a href="{{ route('enrollments.index') }}" class="inline-flex min-h-11 w-full flex-none items-center justify-center whitespace-nowrap rounded-xl border border-secondary/30 bg-surface px-4 py-2.5 text-center text-sm font-medium leading-5 text-secondary transition hover:border-primary hover:text-primary focus:outline-none focus:ring-2 focus:ring-primary/30 sm:w-auto">تسجيل دفعة</a>
            <a href="{{ route('enrollments.create') }}" class="inline-flex min-h-11 w-full flex-none items-center justify-center whitespace-nowrap rounded-xl border border-secondary/30 bg-surface px-4 py-2.5 text-center text-sm font-medium leading-5 text-secondary transition hover:border-primary hover:text-primary focus:outline-none focus:ring-2 focus:ring-primary/30 sm:w-auto">إنشاء اشتراك</a>
            <a href="{{ route('teachers.create') }}" class="inline-flex min-h-11 w-full flex-none items-center justify-center whitespace-nowrap rounded-xl border border-secondary/30 bg-surface px-4 py-2.5 text-center text-sm font-medium leading-5 text-secondary transition hover:border-primary hover:text-primary focus:outline-none focus:ring-2 focus:ring-primary/30 sm:w-auto">إضافة مدرس</a>
            <a href="{{ route('payrolls.index') }}" class="inline-flex min-h-11 w-full flex-none items-center justify-center whitespace-nowrap rounded-xl border border-secondary/30 bg-surface px-4 py-2.5 text-center text-sm font-medium leading-5 text-secondary transition hover:border-primary hover:text-primary focus:outline-none focus:ring-2 focus:ring-primary/30 sm:w-auto">توليد راتب</a>
        </div>
    </section>

    {{-- Recent activity --}}
    <div class="grid gap-4 lg:grid-cols-3">
        <section class="rounded-2xl border border-secondary/15 bg-surface p-5 sm:p-6">
            <h2 class="mb-2 text-sm font-semibold text-secondary">أحدث الطلاب</h2>
            <ul class="divide-y divide-secondary/10 text-sm">
                @forelse ($recent['students'] as $student)
                    <li class="flex items-start justify-between gap-3 py-3 first:pt-2 last:pb-0">
                        <span class="min-w-0 truncate font-medium text-primary">{{ $student->name }}</span>
                        <span class="shrink-0 text-xs text-muted">{{ $student->created_at->diffForHumans() }}</span>
                    </li>
                @empty
                    <li class="py-3 text-sm text-muted">لا يوجد</li>
                @endforelse
            </ul>
        </section>

        <section class="rounded-2xl border border-secondary/15 bg-surface p-5 sm:p-6">
            <h2 class="mb-2 text-sm font-semibold text-secondary">أحدث المدفوعات</h2>
            <ul class="divide-y divide-secondary/10 text-sm">
                @forelse ($recent['payments'] as $payment)
                    <li class="flex items-start justify-between gap-3 py-3 first:pt-2 last:pb-0">
                        <span class="min-w-0 truncate font-medium text-primary">{{ $payment->enrollment->student->name }} — {{ number_format($payment->amount, 2) }}</span>
                        <span class="shrink-0 text-xs text-muted">{{ $payment->created_at->diffForHumans() }}</span>
                    </li>
                @empty
                    <li class="py-3 text-sm text-muted">لا يوجد</li>
                @endforelse
            </ul>
        </section>

        <section class="rounded-2xl border border-secondary/15 bg-surface p-5 sm:p-6">
            <h2 class="mb-2 text-sm font-semibold text-secondary">أحدث الاشتراكات</h2>
            <ul class="divide-y divide-secondary/10 text-sm">
                @forelse ($recent['enrollments'] as $enrollment)
                    <li class="flex items-start justify-between gap-3 py-3 first:pt-2 last:pb-0">
                        <span class="min-w-0 truncate font-medium text-primary">{{ $enrollment->student->name }} — {{ $enrollment->subject->name }}</span>
                        <span class="shrink-0 text-xs text-muted">{{ $enrollment->created_at->diffForHumans() }}</span>
                    </li>
                @empty
                    <li class="py-3 text-sm text-muted">لا يوجد</li>
                @endforelse
            </ul>
        </section>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script>
    function initChart(canvasId, type, labels, data, label) {
        const ctx = document.getElementById(canvasId);
        if (!ctx) return;
        new Chart(ctx, {
            type,
            data: { labels, datasets: [{ label, data, backgroundColor: 'rgba(163, 139, 84, 0.18)', borderColor: '#A38B54' }] },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { precision: 0 } } },
            },
        });
    }
</script>
