<x-layouts.app title="إضافة طالب">
    <h2 class="text-right text-xl font-semibold">إضافة طالب</h2>

    <form dir="rtl" method="POST" action="{{ route('students.store') }}" enctype="multipart/form-data" class="mx-auto mt-6 w-full max-w-2xl space-y-5 rounded-xl border border-secondary/15 bg-surface p-6">
        @csrf
        @include('students._form')

        <button type="submit" class="rounded-lg bg-primary px-5 py-2 font-medium text-white transition hover:bg-primary-hover">
            حفظ
        </button>
    </form>
</x-layouts.app>
