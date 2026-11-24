<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'wesalkom | وصـــــــــالكم' }}</title>
    <link rel="icon" type="image/svg+xml" href="/brand/favicon.svg">
    @include('components.brand.head')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#17212B] text-[#D6DEE6] antialiased">
    <div class="min-h-screen flex flex-col">
        <header class="py-6 px-4 sm:px-6">
            <div class="max-w-7xl mx-auto flex items-center justify-between">
                <x-brand.logo variant="bilingual" tone="dark" size="md" :href="route('home')" />
                <a href="{{ route('home') }}" class="text-sm font-semibold text-[#A8B5C1] hover:text-gold transition">
                    {{ app()->getLocale() === 'ar' ? '← العودة للمتجر' : 'Back to shop →' }}
                </a>
            </div>
        </header>

        <main class="flex-1 flex items-center justify-center px-4 py-8">
            <div class="w-full max-w-md">
                <div class="bg-[#263543] border border-[#344453] rounded-2xl p-6 sm:p-8 shadow-2xl">
                    {{ $slot }}
                </div>
            </div>
        </main>

        <footer class="py-6 text-center text-xs text-[#A8B5C1]">
            &copy; {{ date('Y') }} wesalkom | وصـــــــــالكم
        </footer>
    </div>
</body>
</html>