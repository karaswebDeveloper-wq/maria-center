<nav class="border-b bg-white px-6 py-2">
    <div class="mx-auto flex max-w-6xl gap-4 overflow-x-auto text-sm">
        <a href="{{ route('stages.index') }}" class="whitespace-nowrap rounded-lg px-3 py-1.5 hover:bg-gray-100 {{ request()->routeIs('stages.*') ? 'bg-gray-100 font-medium' : '' }}">المراحل الدراسية</a>
        <a href="{{ route('grades.index') }}" class="whitespace-nowrap rounded-lg px-3 py-1.5 hover:bg-gray-100 {{ request()->routeIs('grades.*') ? 'bg-gray-100 font-medium' : '' }}">الصفوف</a>
        <a href="{{ route('subjects.index') }}" class="whitespace-nowrap rounded-lg px-3 py-1.5 hover:bg-gray-100 {{ request()->routeIs('subjects.*') ? 'bg-gray-100 font-medium' : '' }}">المواد الدراسية</a>
        <a href="{{ route('academic-periods.index') }}" class="whitespace-nowrap rounded-lg px-3 py-1.5 hover:bg-gray-100 {{ request()->routeIs('academic-periods.*') ? 'bg-gray-100 font-medium' : '' }}">الفترات الدراسية</a>
    </div>
</nav>