<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <meta name="theme-color" content="#0a0a0a">
    <title>{{ $title ?? config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@include('components.brand.head')
</head>
<body class="min-h-screen bg-[#17212B] text-gray-100 antialiased flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="flex justify-center mb-8">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
                <div class="w-10 h-10 rounded-lg bg-lime-400 flex items-center justify-center transition-transform group-hover:rotate-6">
                    <svg class="w-5 h-5 text-[#D6DEE6]" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3z"/>
                    </svg>
                </div>
                <span class="text-2xl font-black tracking-tight text-[#D6DEE6]">Libya<span class="text-lime-400">.</span>Market</span>
            </a>
        </div>

        <div class="bg-[#171717] border border-[#344453] rounded-2xl p-6 sm:p-8 shadow-2xl">
            {{ $slot }}
        </div>

        <div class="mt-6 text-center text-xs text-[#A8B5C1]">
            &copy; {{ date('Y') }} WESALKOM
        </div>
    </div>
</body>
</html>