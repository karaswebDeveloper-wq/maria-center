<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'تسجيل الدخول' }} · Maria Center</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-gray-50 font-sans text-gray-900 antialiased">
    <main class="flex min-h-screen items-center justify-center px-4 py-12">
        <section class="w-full max-w-md rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
            <div class="mb-8 text-center">
                <h1 class="text-2xl font-bold">Maria Center</h1>
                <p class="mt-2 text-sm text-gray-600">إدارة المركز التعليمي</p>
            </div>

            {{ $slot }}
        </section>
    </main>

    @livewireScripts
</body>
</html>
