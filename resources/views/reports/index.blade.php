<x-layouts.app title="التقارير">
    <h2 class="text-xl font-semibold">التقارير</h2>

    <div class="mt-6 grid gap-4 sm:grid-cols-2">
        <a href="{{ route('reports.teacher') }}" class="rounded-xl border bg-white p-5 hover:bg-gray-50">
            <h3 class="font-medium">تقرير المدرس</h3>
            <p class="mt-1 text-sm text-gray-500">اشتراكات الطلاب حسب المدرس والمادة والفترة.</p>
        </a>
        <a href="{{ route('reports.payroll') }}" class="rounded-xl border bg-white p-5 hover:bg-gray-50">
            <h3 class="font-medium">التقرير المالي للمدرسين</h3>
            <p class="mt-1 text-sm text-gray-500">معاينة حية لمستحقات المدرسين قبل اعتماد الراتب.</p>
        </a>
        <a href="{{ route('reports.monthly') }}" class="rounded-xl border bg-white p-5 hover:bg-gray-50">
            <h3 class="font-medium">الإحصائيات الشهرية</h3>
            <p class="mt-1 text-sm text-gray-500">توزيع الطلاب والمبالغ حسب المرحلة والصف لفترة معيّنة.</p>
        </a>
        <a href="{{ route('reports.general') }}" class="rounded-xl border bg-white p-5 hover:bg-gray-50">
            <h3 class="font-medium">الإحصائيات العامة</h3>
            <p class="mt-1 text-sm text-gray-500">إجمالي الطلاب حاليًا حسب المرحلة والصف.</p>
        </a>
    </div>
</x-layouts.app>