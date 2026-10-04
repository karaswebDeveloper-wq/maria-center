<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'لوحة التحكم' }} · Maria Center</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-background font-sans text-gray-800 antialiased">
    <header class="border-b border-secondary/15 bg-surface">
        <div dir="rtl" class="mx-auto flex max-w-7xl flex-row items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="flex min-w-0 items-center gap-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/40">
                <span class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-lg font-bold text-primary">م</span>
                <span class="min-w-0">
                    <span class="block truncate text-base font-bold tracking-tight text-gray-900 sm:text-lg">Maria Center</span>
                    <span class="mt-0.5 block text-xs text-muted">إدارة المركز التعليمي</span>
                </span>
            </a>

            <div class="relative shrink-0" x-data="{ open: false }" @keydown.escape.window="open = false">
                <button
                    type="button"
                    @click="open = !open"
                    :aria-expanded="open"
                    aria-haspopup="true"
                    class="flex items-center gap-2 rounded-xl px-2 py-1.5 text-sm text-secondary transition hover:bg-primary/10 hover:text-primary focus:outline-none focus:ring-2 focus:ring-primary/40 sm:px-3"
                >
                    <span class="flex size-8 items-center justify-center rounded-full bg-primary/10 font-semibold text-primary">{{ mb_substr(auth()->user()->name, 0, 1) }}</span>
                    <span class="hidden max-w-36 truncate font-medium sm:inline">{{ auth()->user()->name }}</span>
                    <svg class="size-4 transition-transform" :class="open ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M5.22 7.22a.75.75 0 0 1 1.06 0L10 10.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 8.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                    </svg>
                </button>

                <div
                    x-cloak
                    x-show="open"
                    @click.outside="open = false"
                    x-transition.origin.top.left
                    class="absolute left-0 z-20 mt-2 w-48 rounded-xl border border-secondary/15 bg-surface p-1.5 shadow-sm shadow-gray-900/5"
                >
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full rounded-lg px-3 py-2 text-start text-sm text-secondary transition hover:bg-primary/10 hover:text-primary">
                            تسجيل الخروج
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <nav class="border-b border-secondary/10 bg-surface">
        <div dir="rtl" class="mx-auto flex max-w-7xl flex-row gap-1 overflow-x-auto px-4 py-2 text-sm sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="whitespace-nowrap rounded-lg px-3 py-2 transition {{ request()->routeIs('home') ? 'bg-primary/10 font-medium text-primary' : 'text-secondary hover:text-primary' }}">الرئيسية</a>
            <a href="{{ route('stages.index') }}" class="whitespace-nowrap rounded-lg px-3 py-2 transition {{ request()->routeIs('stages.*') ? 'bg-primary/10 font-medium text-primary' : 'text-secondary hover:text-primary' }}">المراحل الدراسية</a>
            <a href="{{ route('grades.index') }}" class="whitespace-nowrap rounded-lg px-3 py-2 transition {{ request()->routeIs('grades.*') ? 'bg-primary/10 font-medium text-primary' : 'text-secondary hover:text-primary' }}">الصفوف</a>
            <a href="{{ route('subjects.index') }}" class="whitespace-nowrap rounded-lg px-3 py-2 transition {{ request()->routeIs('subjects.*') ? 'bg-primary/10 font-medium text-primary' : 'text-secondary hover:text-primary' }}">المواد الدراسية</a>
            <a href="{{ route('academic-periods.index') }}" class="whitespace-nowrap rounded-lg px-3 py-2 transition {{ request()->routeIs('academic-periods.*') ? 'bg-primary/10 font-medium text-primary' : 'text-secondary hover:text-primary' }}">الفترات الدراسية</a>
        </div>
    </nav>

    <main dir="rtl" class="mx-auto w-full max-w-7xl px-4 py-6 text-right sm:px-6 sm:py-8 lg:px-8">
        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>
