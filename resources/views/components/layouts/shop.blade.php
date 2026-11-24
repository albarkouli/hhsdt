<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $isAr = app()->getLocale() === 'ar';
        $pageTitle = $title ?? ($isAr ? 'ط¸ث†ط·آµط·آ§ط¸â€‍ط¸ئ’ط¸â€¦ | ط¸ئ’ط¸â€‍ ط¸â€¦ط·آ§ ط·ع¾ط·آ­ط·آ¨ط·إ’ ط·آ£ط¸â€ڑط·آ±ط·آ¨ ط·آ¥ط¸â€‍ط¸ظ¹ط¸ئ’' : 'WESALKOM | Everything You Love, Closer');
        $metaDesc = $isAr
            ? 'ط¸ث†ط·آµط·آ§ط¸â€‍ط¸ئ’ط¸â€¦ ط¸â€¦ط¸â€ ط·آµط·آ© ط·ع¾ط·آ³ط¸ث†ط¸â€ڑ ط·ع¾ط·آ¬ط¸â€¦ط·آ¹ ط¸â€‍ط¸ئ’ ط·آ§ط¸â€‍ط¸â€¦ط·ع¾ط·آ§ط·آ¬ط·آ± ط¸ث†ط·آ§ط¸â€‍ط¸â€¦ط¸â€ ط·ع¾ط·آ¬ط·آ§ط·ع¾ ط¸ظ¾ط¸ظ¹ ط¸â€¦ط¸ئ’ط·آ§ط¸â€  ط¸ث†ط·آ§ط·آ­ط·آ¯ط·إ’ ط¸â€‍ط·ع¾ط¸ئ’ط·ع¾ط·آ´ط¸ظ¾ ط·آ®ط¸ظ¹ط·آ§ط·آ±ط·آ§ط·ع¾ ط¸â€¦ط·ع¾ط¸â€ ط¸ث†ط·آ¹ط·آ© ط¸ث†ط·ع¾ط·آ³ط·ع¾ط¸â€¦ط·ع¾ط·آ¹ ط·آ¨ط·ع¾ط·آ¬ط·آ±ط·آ¨ط·آ© ط·ع¾ط·آ³ط¸ث†ط¸â€ڑ ط·آ£ط·آ³ط¸â€،ط¸â€‍.'
            : 'WESALKOM brings stores and products together in one place, making it easier to discover and shop what you love.';
    @endphp

    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $metaDesc }}">
    <meta property="og:type" content="website">
    <meta property="og:title" content="Libya Market">
    <meta property="og:description" content="{{ $metaDesc }}">
    <meta property="og:site_name" content="WESALKOM">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $metaDesc }}">
    <link rel="canonical" href="{{ url()->current() }}">

    @include('components.brand.head')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#17212B] text-[#D6DEE6] antialiased flex flex-col">
    @php
        $cartCount = array_sum(session('cart', []));
        $unreadCount = auth()->check() ? auth()->user()->unreadNotifications->count() : 0;
        $wishlistCount = auth()->check() ? auth()->user()->wishlist()->count() : 0;
    @endphp

    <header class="sticky top-0 z-50 bg-[#1F2A36] border-b border-[#344453]">
        <div class="bg-navy text-[#D6DEE6]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-1.5 flex items-center justify-between text-xs font-semibold">
                <span class="flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    {{ $isAr ? 'ط·آ´ط·آ­ط¸â€  ط¸â€¦ط·آ¬ط·آ§ط¸â€ ط¸ظ¹ ط¸â€‍ط¸â€‍ط·آ·ط¸â€‍ط·آ¨ط·آ§ط·ع¾ ط¸ظ¾ط¸ث†ط¸â€ڑ 500 ط·آ¯.ط¸â€‍ | ط·آ§ط¸â€‍ط·آ¯ط¸ظ¾ط·آ¹ ط·آ¹ط¸â€ ط·آ¯ ط·آ§ط¸â€‍ط·آ§ط·آ³ط·ع¾ط¸â€‍ط·آ§ط¸â€¦' : 'Free shipping over 500 LYD | Cash on delivery
                </span>
                <div class="hidden sm:flex items-center gap-4">
                    <a href="{{ route('shop.contact') }}" class="hover:text-gold transition">{{ $isAr ? 'ط¸â€¦ط·آ³ط·آ§ط·آ¹ط·آ¯ط·آ©' : 'Help' }}</a>
                    @auth
                        <a href="{{ route('shop.orders.index') }}" class="hover:text-gold transition">{{ $isAr ? 'ط·ع¾ط·ع¾ط·آ¨ط·آ¹ ط·آ§ط¸â€‍ط·آ·ط¸â€‍ط·آ¨' : 'Track order' }}</a>
                    @endauth
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-3 md:gap-4 h-16 md:h-20">
                <x-brand.logo variant="bilingual" tone="light" size="md" :href="route('home')" />

                <form action="{{ route('shop.products.index') }}" method="GET" class="flex-1 max-w-xl hidden md:block">
                    <div class="relative">
                        <svg class="absolute {{ $isAr ? 'right-4' : 'left-4' }} top-1/2 -translate-y-1/2 w-4 h-4 text-[#A8B5C1] pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="search" name="q" placeholder="{{ $isAr ? 'ط·آ§ط·آ¨ط·آ­ط·آ« ط·آ¹ط¸â€  ط¸â€¦ط¸â€ ط·ع¾ط·آ¬ط·آ§ط·ع¾...' : 'Search products...' }}" class="w-full rounded-full bg-[#17212B] border border-[#344453] {{ $isAr ? 'pr-11 pl-4' : 'pl-11 pr-4' }} py-2.5 text-sm text-[#D6DEE6] placeholder-gray-400 focus:border-gold focus:ring-2 focus:ring-gold/20 focus:bg-[#263543] transition">
                    </div>
                </form>

                <div class="flex items-center gap-1 ms-auto">
                    @auth
                        <a href="{{ route('shop.wishlist.index') }}" class="relative p-2.5 rounded-lg text-[#D6DEE6] hover:bg-[#17212B] hover:text-gold transition" aria-label="Wishlist">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            @if ($wishlistCount > 0)<span class="absolute top-1 {{ $isAr ? 'left-1' : 'right-1' }} bg-gold text-[#D6DEE6] text-[10px] font-black rounded-full min-w-[16px] h-4 flex items-center justify-center px-1">{{ $wishlistCount }}</span>@endif
                        </a>
                        <a href="{{ route('notifications.index') }}" class="relative p-2.5 rounded-lg text-[#D6DEE6] hover:bg-[#17212B] hover:text-gold transition" aria-label="Notifications">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            @if ($unreadCount > 0)<span class="absolute top-1 {{ $isAr ? 'left-1' : 'right-1' }} bg-error text-[#D6DEE6] text-[10px] font-black rounded-full min-w-[16px] h-4 flex items-center justify-center px-1">{{ $unreadCount }}</span>@endif
                        </a>
                    @endauth
                    <a href="{{ route('shop.cart.index') }}" class="relative p-2.5 rounded-lg text-[#D6DEE6] hover:bg-[#17212B] hover:text-gold transition" aria-label="Cart">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        @if ($cartCount > 0)<span class="absolute top-1 {{ $isAr ? 'left-1' : 'right-1' }} bg-gold text-[#D6DEE6] text-[10px] font-black rounded-full min-w-[16px] h-4 flex items-center justify-center px-1">{{ $cartCount }}</span>@endif
                    </a>

                    @auth
                        <a href="{{ route('dashboard') }}" class="ms-2 flex items-center gap-2 py-1 rounded-full hover:bg-[#17212B] transition">
                            <div class="w-8 h-8 rounded-full bg-navy text-[#D6DEE6] flex items-center justify-center text-sm font-black">{{ Str::substr(auth()->user()->name, 0, 1) }}</div>
                            <span class="hidden lg:block text-sm font-semibold text-[#D6DEE6] me-2">{{ Str::limit(auth()->user()->name, 12) }}</span>
                        </a>
                    @else
                        <div class="flex items-center gap-2 ms-2">
                            <a href="{{ route('login') }}" class="text-sm font-semibold text-[#D6DEE6] hover:text-gold px-2 py-1.5 transition">{{ $isAr ? 'ط·آ¯ط·آ®ط¸ث†ط¸â€‍' : 'Login' }}</a>
                            <a href="{{ route('register') }}" class="text-sm font-black bg-gold text-[#D6DEE6] px-4 py-2 rounded-lg hover:bg-gold-hover transition">{{ $isAr ? 'ط·آ¥ط¸â€ ط·آ´ط·آ§ط·طŒ ط·آ­ط·آ³ط·آ§ط·آ¨' : 'Sign up' }}</a>
                        </div>
                    @endauth

                    <button id="mobile-menu-btn" type="button" class="md:hidden p-2 rounded-lg text-[#D6DEE6] hover:bg-[#17212B] transition" aria-label="Menu">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <div id="shop-mobile-panel" class="hidden md:hidden bg-[#263543] border-b border-[#344453] shadow-sm">
        <div class="px-4 py-3 space-y-1">
            <form action="{{ route('shop.products.index') }}" method="GET" class="mb-3">
                <div class="relative">
                    <svg class="absolute {{ $isAr ? 'right-4' : 'left-4' }} top-1/2 -translate-y-1/2 w-4 h-4 text-[#A8B5C1] pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="search" name="q" placeholder="{{ $isAr ? 'ط·آ§ط·آ¨ط·آ­ط·آ«...' : 'Search...' }}" class="w-full rounded-full bg-[#17212B] border border-[#344453] {{ $isAr ? 'pr-11 pl-4' : 'pl-11 pr-4' }} py-2 text-sm text-[#D6DEE6]">
                </div>
            </form>
            <a href="{{ route('shop.products.index') }}" class="block px-3 py-2.5 text-sm font-semibold text-[#D6DEE6] hover:bg-[#17212B] hover:text-gold rounded-md transition">{{ $isAr ? 'ط¸ئ’ط¸â€‍ ط·آ§ط¸â€‍ط¸â€¦ط¸â€ ط·ع¾ط·آ¬ط·آ§ط·ع¾' : 'Shop All' }}</a>
            <a href="{{ route('shop.about') }}" class="block px-3 py-2.5 text-sm font-semibold text-[#D6DEE6] hover:bg-[#17212B] hover:text-gold rounded-md transition">{{ $isAr ? 'ط¸â€¦ط¸â€  ط¸â€ ط·آ­ط¸â€ ' : 'About' }}</a>
            <a href="{{ route('shop.contact') }}" class="block px-3 py-2.5 text-sm font-semibold text-[#D6DEE6] hover:bg-[#17212B] hover:text-gold rounded-md transition">{{ $isAr ? 'ط·ع¾ط¸ث†ط·آ§ط·آµط¸â€‍ ط¸â€¦ط·آ¹ط¸â€ ط·آ§' : 'Contact' }}</a>
            @auth
                <a href="{{ route('shop.orders.index') }}" class="block px-3 py-2.5 text-sm font-semibold text-[#D6DEE6] hover:bg-[#17212B] hover:text-gold rounded-md transition">{{ $isAr ? 'ط·آ·ط¸â€‍ط·آ¨ط·آ§ط·ع¾ط¸ظ¹' : 'My Orders' }}</a>
                <a href="{{ route('shop.wishlist.index') }}" class="block px-3 py-2.5 text-sm font-semibold text-[#D6DEE6] hover:bg-[#17212B] hover:text-gold rounded-md transition">{{ $isAr ? 'ط·آ§ط¸â€‍ط¸â€¦ط¸ظ¾ط·آ¶ط¸â€‍ط·آ©' : 'Wishlist' }}</a>
                <a href="{{ route('dashboard') }}" class="block px-3 py-2.5 text-sm font-semibold text-[#D6DEE6] hover:bg-[#17212B] hover:text-gold rounded-md transition">{{ $isAr ? 'ط¸â€‍ط¸ث†ط·آ­ط·آ© ط·آ§ط¸â€‍ط·ع¾ط·آ­ط¸ئ’ط¸â€¦' : 'Dashboard' }}</a>
            @else
                <a href="{{ route('login') }}" class="block px-3 py-2.5 text-sm font-semibold text-[#D6DEE6] hover:bg-[#17212B] hover:text-gold rounded-md transition">{{ $isAr ? 'ط·آ¯ط·آ®ط¸ث†ط¸â€‍' : 'Login' }}</a>
                <a href="{{ route('register') }}" class="block px-3 py-2.5 text-sm font-semibold bg-gold text-[#D6DEE6] rounded-md">{{ $isAr ? 'ط·آ¥ط¸â€ ط·آ´ط·آ§ط·طŒ ط·آ­ط·آ³ط·آ§ط·آ¨' : 'Sign up' }}</a>
            @endauth
            <a href="{{ route('vendor.register') }}" class="block px-3 py-2.5 mt-2 text-xs font-black text-gold hover:bg-[#17212B] rounded-md transition">{{ $isAr ? 'ط·آ¨ط¸ظ¹ط·آ¹ ط·آ¹ط¸â€‍ط¸â€° ط¸ث†ط·آµط·آ§ط¸â€‍ط¸ئ’ط¸â€¦ أ¢â€ ع¯' : 'Sell on WESALKOM أ¢â€ â€™' }}</a>
        </div>
    </div>

    <div class="bg-[#263543] border-b border-[#344453]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex items-center gap-1 h-11 text-sm overflow-x-auto">
                <a href="{{ route('shop.products.index') }}" class="px-3 py-1.5 rounded-md font-semibold text-[#D6DEE6] hover:bg-[#17212B] hover:text-gold whitespace-nowrap transition">{{ $isAr ? 'ط¸ئ’ط¸â€‍ ط·آ§ط¸â€‍ط¸â€¦ط¸â€ ط·ع¾ط·آ¬ط·آ§ط·ع¾' : 'Shop All' }}</a>
                <a href="{{ route('shop.about') }}" class="px-3 py-1.5 rounded-md font-semibold text-[#A8B5C1] hover:bg-[#17212B] hover:text-gold whitespace-nowrap transition">{{ $isAr ? 'ط¸â€¦ط¸â€  ط¸â€ ط·آ­ط¸â€ ' : 'About' }}</a>
                <a href="{{ route('shop.contact') }}" class="px-3 py-1.5 rounded-md font-semibold text-[#A8B5C1] hover:bg-[#17212B] hover:text-gold whitespace-nowrap transition">{{ $isAr ? 'ط·ع¾ط¸ث†ط·آ§ط·آµط¸â€‍' : 'Contact' }}</a>
                @auth
                    <a href="{{ route('shop.orders.index') }}" class="px-3 py-1.5 rounded-md font-semibold text-[#A8B5C1] hover:bg-[#17212B] hover:text-gold whitespace-nowrap transition">{{ $isAr ? 'ط·آ·ط¸â€‍ط·آ¨ط·آ§ط·ع¾ط¸ظ¹' : 'My Orders' }}</a>
                @endauth
                <div class="ms-auto hidden md:flex items-center gap-2">
                    <span class="text-xs text-[#A8B5C1]">{{ $isAr ? 'ط·ع¾ط·آ¨ط¸ظ¹ط·آ¹ ط·آ¹ط¸â€‍ط¸â€° ط¸ث†ط·آµط·آ§ط¸â€‍ط¸ئ’ط¸â€¦ط·ع؛' : 'Sell on WESALKOM?' }}</span>
                    <a href="{{ route('vendor.register') }}" class="text-xs font-black text-gold hover:text-gold-hover transition">{{ $isAr ? 'ط·آ§ط¸ظ¾ط·ع¾ط·آ­ ط¸â€¦ط·ع¾ط·آ¬ط·آ±ط¸ئ’ أ¢â€ ع¯' : 'Open a store أ¢â€ â€™' }}</a>
                </div>
            </nav>
        </div>
    </div>

    <main class="flex-1 max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
        @if (session('cart_status'))
            <div class="mb-5 rounded-lg border border-success/30 bg-success-soft p-3 text-sm text-success flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ session('cart_status') }}
            </div>
        @endif
        @if (session('cart_error'))
            <div class="mb-5 rounded-lg border border-error/30 bg-error-soft p-3 text-sm text-error flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('cart_error') }}
            </div>
        @endif        {{ $slot }}
    </main>

    <footer class="bg-[#1F2A36] text-[#D6DEE6] mt-16 border-t border-[#344453]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10 mb-12">
                <div class="md:col-span-2">
                    <x-brand.logo variant="bilingual" tone="dark" size="md" :href="route('home')" class="mb-5" />
                    <p class="text-sm text-[#D6DEE6]/70 max-w-md leading-relaxed">
                        {{ $isAr ? 'ط¸ث†ط·آµط·آ§ط¸â€‍ط¸ئ’ط¸â€¦ ط¸â€¦ط¸â€ ط·آµط·آ© ط·ع¾ط·آ³ط¸ث†ط¸â€ڑ ط·ع¾ط·آ¬ط¸â€¦ط·آ¹ ط¸â€‍ط¸ئ’ ط·آ§ط¸â€‍ط¸â€¦ط·ع¾ط·آ§ط·آ¬ط·آ± ط¸ث†ط·آ§ط¸â€‍ط¸â€¦ط¸â€ ط·ع¾ط·آ¬ط·آ§ط·ع¾ ط¸ظ¾ط¸ظ¹ ط¸â€¦ط¸ئ’ط·آ§ط¸â€  ط¸ث†ط·آ§ط·آ­ط·آ¯ط·إ’ ط¸â€‍ط·ع¾ط¸ئ’ط·ع¾ط·آ´ط¸ظ¾ ط·آ®ط¸ظ¹ط·آ§ط·آ±ط·آ§ط·ع¾ ط¸â€¦ط·ع¾ط¸â€ ط¸ث†ط·آ¹ط·آ© ط¸ث†ط·ع¾ط·آ³ط·ع¾ط¸â€¦ط·ع¾ط·آ¹ ط·آ¨ط·ع¾ط·آ¬ط·آ±ط·آ¨ط·آ© ط·ع¾ط·آ³ط¸ث†ط¸â€ڑ ط·آ£ط·آ³ط¸â€،ط¸â€‍.' : 'WESALKOM brings stores and products together in one place, making it easier to discover and shop what you love.' }}
                    </p>
                    <div class="flex gap-2 mt-5">
                        <a href="https://facebook.com/wesalkom" target="_blank" rel="noopener" class="w-9 h-9 rounded-lg bg-[#263543]/5 border border-[#344453]/10 flex items-center justify-center text-[#D6DEE6]/60 hover:text-gold hover:border-gold/30 transition" aria-label="Facebook"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></a>
                        <a href="https://instagram.com/wesalkom" target="_blank" rel="noopener" class="w-9 h-9 rounded-lg bg-[#263543]/5 border border-[#344453]/10 flex items-center justify-center text-[#D6DEE6]/60 hover:text-gold hover:border-gold/30 transition" aria-label="Instagram"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zM12 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg></a>
                        <a href="https://twitter.com/wesalkom" target="_blank" rel="noopener" class="w-9 h-9 rounded-lg bg-[#263543]/5 border border-[#344453]/10 flex items-center justify-center text-[#D6DEE6]/60 hover:text-gold hover:border-gold/30 transition" aria-label="Twitter"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg></a>
                    </div>
                </div>
                <div>
                    <h3 class="text-[#D6DEE6] font-black text-sm uppercase tracking-widest mb-4">{{ $isAr ? 'ط·آ§ط·آ³ط·ع¾ط¸ئ’ط·آ´ط¸ظ¾' : 'Explore' }}</h3>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ route('shop.products.index') }}" class="text-[#D6DEE6]/70 hover:text-gold transition">{{ $isAr ? 'ط·آ§ط¸â€‍ط¸â€¦ط·ع¾ط·آ¬ط·آ±' : 'Shop' }}</a></li>
                        <li><a href="{{ route('shop.about') }}" class="text-[#D6DEE6]/70 hover:text-gold transition">{{ $isAr ? 'ط¸â€¦ط¸â€  ط¸â€ ط·آ­ط¸â€ ' : 'About us' }}</a></li>
                        <li><a href="{{ route('shop.contact') }}" class="text-[#D6DEE6]/70 hover:text-gold transition">{{ $isAr ? 'ط·ع¾ط¸ث†ط·آ§ط·آµط¸â€‍ ط¸â€¦ط·آ¹ط¸â€ ط·آ§' : 'Contact' }}</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-[#D6DEE6] font-black text-sm uppercase tracking-widest mb-4">{{ $isAr ? 'ط·آ¨ط¸ع¯ط·آ¹ ط¸â€¦ط·آ¹ط¸â€ ط·آ§' : 'Sell with us' }}</h3>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ route('register') }}" class="text-[#D6DEE6]/70 hover:text-gold transition">{{ $isAr ? 'ط·آ¥ط¸â€ ط·آ´ط·آ§ط·طŒ ط·آ­ط·آ³ط·آ§ط·آ¨' : 'Create account' }}</a></li>
                        @auth
                            @if (! auth()->user()->vendor)
                                <li><a href="{{ route('vendor.register') }}" class="text-[#D6DEE6]/70 hover:text-gold transition">{{ $isAr ? 'ط·آ§ط¸ظ¾ط·ع¾ط·آ­ ط¸â€¦ط·ع¾ط·آ¬ط·آ±ط¸â€¹ط·آ§' : 'Open a store' }}</a></li>
                            @endif
                        @else
                            <li><a href="{{ route('vendor.register') }}" class="text-[#D6DEE6]/70 hover:text-gold transition">{{ $isAr ? 'ط·آ§ط¸ظ¾ط·ع¾ط·آ­ ط¸â€¦ط·ع¾ط·آ¬ط·آ±ط¸â€¹ط·آ§' : 'Open a store' }}</a></li>
                        @endauth
                    </ul>
                </div>
            </div>
            <div class="pt-8 border-t border-[#344453]/10 flex flex-col sm:flex-row justify-between items-center gap-3 text-xs text-[#D6DEE6]/50">
                <p>&copy; {{ date('Y') }} WESALKOM | ط¸ث†ط·آµط·آ§ط¸â€‍ط¸ئ’ط¸â€¦. {{ $isAr ? 'ط·آ¬ط¸â€¦ط¸ظ¹ط·آ¹ ط·آ§ط¸â€‍ط·آ­ط¸â€ڑط¸ث†ط¸â€ڑ ط¸â€¦ط·آ­ط¸ظ¾ط¸ث†ط·آ¸ط·آ©.' : 'All rights reserved.' }}</p>
                <p>{{ $isAr ? 'ط·آµط¸عˆط¸â€ ط·آ¹ ط¸ظ¾ط¸ظ¹ ط¸â€‍ط¸ظ¹ط·آ¨ط¸ظ¹ط·آ§ ط·آ¨ط¸â‚¬' : 'Made in Libya with' }} <span class="text-gold">&hearts;</span></p>
            </div>
        </div>
    </footer>

    <script>
    (function() {
        var btn = document.getElementById('mobile-menu-btn');
        var panel = document.getElementById('shop-mobile-panel');
        if (btn && panel) {
            btn.addEventListener('click', function() { panel.classList.toggle('hidden'); });
        }
    })();
    </script>
</body>
</html>