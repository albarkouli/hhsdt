<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} — wesalkom | وصـــــــــالكم</title>
    <link rel="icon" type="image/svg+xml" href="/brand/favicon.svg">
    @include('components.brand.head')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#17212B] text-[#D6DEE6] antialiased">
    @php
        $isAr = app()->getLocale() === 'ar';
        $user = auth()->user();
    @endphp

    <div class="flex min-h-screen">
        {{-- SIDEBAR --}}
        <aside id="dash-sidebar" class="hidden lg:flex flex-col w-64 bg-[#1F2A36] border-e border-[#344453] shrink-0">
            <div class="h-16 flex items-center px-5 border-b border-[#344453]">
                <x-brand.logo variant="bilingual" tone="dark" size="sm" :href="route('home')" />
            </div>

            <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
                @php
                    $navItems = [
                        ['route' => 'dashboard', 'label_en' => 'Overview', 'label_ar' => 'نظرة عامة', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                    ];
                    if ($user?->vendor) {
                        $navItems = array_merge($navItems, [
                            ['route' => 'vendor.products.index', 'label_en' => 'Products', 'label_ar' => 'المنتجات', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                            ['route' => 'vendor.orders.index', 'label_en' => 'Orders', 'label_ar' => 'الطلبات', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                            ['route' => 'vendor.reviews.index', 'label_en' => 'Reviews', 'label_ar' => 'التقييمات', 'icon' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z'],
                            ['route' => 'vendor.settings.edit', 'label_en' => 'Store settings', 'label_ar' => 'إعدادات المتجر', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'],
                        ]);
                    }
                    if ($user?->hasRole('super_admin')) {
                        $navItems = array_merge($navItems, [
                            ['route' => 'admin.dashboard', 'label_en' => 'Admin home', 'label_ar' => 'الرئيسية', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                            ['route' => 'admin.vendors.index', 'label_en' => 'Vendors', 'label_ar' => 'المتاجر', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                            ['route' => 'admin.orders.index', 'label_en' => 'Orders', 'label_ar' => 'الطلبات', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                            ['route' => 'admin.categories.index', 'label_en' => 'Categories', 'label_ar' => 'التصنيفات', 'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z'],
                            ['route' => 'admin.coupons.index', 'label_en' => 'Coupons', 'label_ar' => 'الكوبونات', 'icon' => 'M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z'],
                        ]);
                    }
                @endphp

                @foreach ($navItems as $item)
                    @php
                        try { $href = route($item['route']); } catch (\Exception $e) { continue; }
                        $active = request()->routeIs($item['route']) || request()->routeIs(str_replace('.index', '.*', $item['route']));
                    @endphp
                    <a href="{{ $href }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition {{ $active ? 'bg-gold/15 text-gold border border-gold/30' : 'text-[#A8B5C1] hover:bg-[#263543] hover:text-[#D6DEE6]' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                        </svg>
                        <span>{{ $isAr ? $item['label_ar'] : $item['label_en'] }}</span>
                    </a>
                @endforeach
            </nav>

            <div class="p-3 border-t border-[#344453]">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-semibold text-[#A8B5C1] hover:bg-error/10 hover:text-error transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        {{ $isAr ? 'تسجيل الخروج' : 'Logout' }}
                    </button>
                </form>
            </div>
        </aside>

        {{-- MAIN --}}
        <div class="flex-1 flex flex-col min-w-0">
            <header class="h-16 bg-[#1F2A36] border-b border-[#344453] flex items-center px-4 sm:px-6 gap-4">
                <button id="dash-menu-btn" type="button" class="lg:hidden p-2 rounded-lg text-[#D6DEE6] hover:bg-[#263543]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <h1 class="text-base font-bold text-[#D6DEE6] truncate">{{ $title ?? ($isAr ? 'لوحة التحكم' : 'Dashboard') }}</h1>
                <div class="ms-auto flex items-center gap-3">
                    <a href="{{ route('notifications.index') }}" class="relative p-2 rounded-lg text-[#A8B5C1] hover:text-gold hover:bg-[#263543]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    </a>
                    <div class="w-9 h-9 rounded-full bg-gold text-[#17212B] flex items-center justify-center font-black text-sm">
                        {{ Str::substr($user?->name ?? '?', 0, 1) }}
                    </div>
                </div>
            </header>

            <main class="flex-1 p-4 sm:p-6 overflow-y-auto">
                {{ $slot }}
            </main>
        </div>
    </div>

    <script>
    (function() {
        var btn = document.getElementById('dash-menu-btn');
        var sb = document.getElementById('dash-sidebar');
        if (btn && sb) {
            btn.addEventListener('click', function() {
                sb.classList.toggle('hidden');
                sb.classList.toggle('fixed');
                sb.classList.toggle('inset-y-0');
                sb.classList.toggle('start-0');
                sb.classList.toggle('z-50');
            });
        }
    })();
    </script>
</body>
</html>