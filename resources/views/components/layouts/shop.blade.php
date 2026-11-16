<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <meta name="theme-color" content="#0a0a0a">
    <meta name="description" content="Libya's fastest-growing online marketplace. Shop from trusted Libyan vendors with cash on delivery.">
    <meta property="og:type" content="website">
    <meta property="og:title" content="Libya Market â€” Shop Local">
    <meta property="og:description" content="Shop from trusted Libyan vendors. Cash on delivery.">
    <meta property="og:site_name" content="Libya Market">
    <meta name="twitter:card" content="summary_large_image">
    <title>{{ $title ?? 'Libya Marketplace' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Cairo:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#0a0a0a] text-gray-100 antialiased flex flex-col">
    @php
        $cartCount = array_sum(session('cart', []));
        $unreadCount = auth()->check() ? auth()->user()->unreadNotifications->count() : 0;
        $wishlistCount = auth()->check() ? auth()->user()->wishlist()->count() : 0;
    @endphp

    <header class="sticky top-0 z-50 bg-[#0a0a0a]/95 backdrop-blur-md border-b border-gray-800">
        <div class="bg-lime-400 text-black">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-1.5 flex items-center justify-between text-xs font-semibold">
                <span class="flex items-center gap-2">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Free shipping over 500 LYD ط¢آ· Cash on delivery
                </span>
                <div class="hidden sm:flex items-center gap-4">
                    <a href="{{ route('shop.contact') }}" class="hover:underline">Help</a>
                    @auth
                        <a href="{{ route('shop.orders.index') }}" class="hover:underline">Track order</a>
                    @endauth
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-4 h-16">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 shrink-0 group">
                    <div class="w-9 h-9 rounded-lg bg-lime-400 flex items-center justify-center transition-transform group-hover:rotate-6">
                        <svg class="w-5 h-5 text-black" fill="currentColor" viewBox="0 0 20 20"><path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3z"/></svg>
                    </div>
                    <span class="text-lg font-black tracking-tight text-white">Libya<span class="text-lime-400">.</span>Market</span>
                </a>

                <form action="{{ route('shop.products.index') }}" method="GET" class="flex-1 max-w-xl hidden md:block">
                    <div class="relative">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="search" name="q" placeholder="Search products..." class="w-full rounded-full bg-gray-900 border border-gray-800 pl-11 pr-4 py-2.5 text-sm text-white placeholder-gray-500 focus:border-lime-400 focus:ring-2 focus:ring-lime-400/20 focus:bg-black">
                    </div>
                </form>

                <div class="flex items-center gap-1 ml-auto">
                    @auth
                        <a href="{{ route('shop.wishlist.index') }}" class="relative p-2.5 rounded-lg text-gray-300 hover:bg-gray-900 hover:text-lime-400" aria-label="Wishlist">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            @if ($wishlistCount > 0)<span class="absolute top-1 right-1 bg-lime-400 text-black text-[10px] font-black rounded-full min-w-[16px] h-4 flex items-center justify-center px-1">{{ $wishlistCount }}</span>@endif
                        </a>
                        <a href="{{ route('notifications.index') }}" class="relative p-2.5 rounded-lg text-gray-300 hover:bg-gray-900 hover:text-lime-400" aria-label="Notifications">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            @if ($unreadCount > 0)<span class="absolute top-1 right-1 bg-red-500 text-white text-[10px] font-black rounded-full min-w-[16px] h-4 flex items-center justify-center px-1">{{ $unreadCount }}</span>@endif
                        </a>
                    @endauth
                    <a href="{{ route('shop.cart.index') }}" class="relative p-2.5 rounded-lg text-gray-300 hover:bg-gray-900 hover:text-lime-400" aria-label="Cart">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        @if ($cartCount > 0)<span class="absolute top-1 right-1 bg-lime-400 text-black text-[10px] font-black rounded-full min-w-[16px] h-4 flex items-center justify-center px-1">{{ $cartCount }}</span>@endif
                    </a>

                    @auth
                        <a href="{{ route('dashboard') }}" class="ml-2 flex items-center gap-2 pl-1 pr-3 py-1 rounded-full hover:bg-gray-900">
                            <div class="w-8 h-8 rounded-full bg-lime-400 text-black flex items-center justify-center text-sm font-black">{{ Str::substr(auth()->user()->name, 0, 1) }}</div>
                            <span class="hidden lg:block text-sm font-semibold text-white">{{ Str::limit(auth()->user()->name, 12) }}</span>
                        </a>
                    @else
                        <div class="flex items-center gap-2 ml-2">
                            <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-300 hover:text-white px-2 py-1.5">Login</a>
                            <a href="{{ route('register') }}" class="text-sm font-black bg-lime-400 text-black px-4 py-2 rounded-lg hover:bg-lime-300">Sign up</a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <div id="shop-mobile-panel" class="hidden md:hidden bg-[#0a0a0a] border-b border-gray-800">
        <div class="px-4 py-3 space-y-1">
            <a href="{{ route('shop.products.index') }}" class="block px-3 py-2.5 text-sm font-semibold text-gray-300 hover:bg-gray-900 hover:text-lime-400 rounded-md">Shop All</a>
            <a href="{{ route('shop.about') }}" class="block px-3 py-2.5 text-sm font-semibold text-gray-300 hover:bg-gray-900 hover:text-lime-400 rounded-md">About</a>
            <a href="{{ route('shop.contact') }}" class="block px-3 py-2.5 text-sm font-semibold text-gray-300 hover:bg-gray-900 hover:text-lime-400 rounded-md">Contact</a>
            @auth
                <a href="{{ route('shop.orders.index') }}" class="block px-3 py-2.5 text-sm font-semibold text-gray-300 hover:bg-gray-900 hover:text-lime-400 rounded-md">My Orders</a>
                <a href="{{ route('shop.wishlist.index') }}" class="block px-3 py-2.5 text-sm font-semibold text-gray-300 hover:bg-gray-900 hover:text-lime-400 rounded-md">Wishlist</a>
                <a href="{{ route('dashboard') }}" class="block px-3 py-2.5 text-sm font-semibold text-gray-300 hover:bg-gray-900 hover:text-lime-400 rounded-md">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="block px-3 py-2.5 text-sm font-semibold text-gray-300 hover:bg-gray-900 hover:text-lime-400 rounded-md">Login</a>
                <a href="{{ route('register') }}" class="block px-3 py-2.5 text-sm font-semibold bg-lime-400 text-black rounded-md">Sign up</a>
            @endauth
            <a href="{{ route('vendor.register') }}" class="block px-3 py-2.5 mt-2 text-xs font-black text-lime-400 hover:bg-gray-900 rounded-md">Sell on Libya Market &rarr;</a>
        </div>
    </div>
    <div class="bg-[#0a0a0a] border-b border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex items-center gap-1 h-11 text-sm overflow-x-auto">
                <a href="{{ route('shop.products.index') }}" class="px-3 py-1.5 rounded-md font-semibold text-white hover:bg-gray-900 hover:text-lime-400 whitespace-nowrap">Shop All</a>
                <a href="{{ route('shop.about') }}" class="px-3 py-1.5 rounded-md font-semibold text-gray-400 hover:bg-gray-900 hover:text-lime-400 whitespace-nowrap">About</a>
                <a href="{{ route('shop.contact') }}" class="px-3 py-1.5 rounded-md font-semibold text-gray-400 hover:bg-gray-900 hover:text-lime-400 whitespace-nowrap">Contact</a>
                @auth
                    <a href="{{ route('shop.orders.index') }}" class="px-3 py-1.5 rounded-md font-semibold text-gray-400 hover:bg-gray-900 hover:text-lime-400 whitespace-nowrap">My Orders</a>
                @endauth
                <div class="ml-auto hidden md:flex items-center gap-2">
                    <span class="text-xs text-gray-500">Sell on Libya Market?</span>
                    <a href="{{ route('vendor.register') }}" class="text-xs font-black text-lime-400 hover:text-lime-300">Open a store &rarr;</a>
                </div>
            </nav>
        </div>
    </div>

    <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
        @if (session('cart_status'))
            <div class="mb-5 rounded-lg border border-lime-400/30 bg-lime-400/10 p-3 text-sm text-lime-300 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ session('cart_status') }}
            </div>
        @endif
        @if (session('cart_error'))
            <div class="mb-5 rounded-lg border border-red-500/30 bg-red-500/10 p-3 text-sm text-red-300 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('cart_error') }}
            </div>
        @endif
        {{ $slot }}
    </main>

    <footer class="bg-[#0a0a0a] border-t border-gray-800 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10 mb-12">
                <div class="md:col-span-2">
                    <a href="{{ route('home') }}" class="flex items-center gap-2.5 mb-4">
                        <div class="w-9 h-9 rounded-lg bg-lime-400 flex items-center justify-center">
                            <svg class="w-5 h-5 text-black" fill="currentColor" viewBox="0 0 20 20"><path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3z"/></svg>
                        </div>
                        <span class="text-lg font-black tracking-tight text-white">Libya<span class="text-lime-400">.</span>Market</span>
                    </a>
                    <p class="text-sm text-gray-400 max-w-md leading-relaxed">Libya's fastest-growing online marketplace. Support local vendors, pay with confidence.</p>
                    <div class="flex gap-2 mt-5">
                        <a href="https://facebook.com/libyamarketplace" target="_blank" rel="noopener" class="w-9 h-9 rounded-lg bg-gray-900 border border-gray-800 flex items-center justify-center text-gray-400 hover:text-lime-400 hover:border-lime-400/30 transition" aria-label="Facebook"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></a>
                        <a href="https://instagram.com/libyamarketplace" target="_blank" rel="noopener" class="w-9 h-9 rounded-lg bg-gray-900 border border-gray-800 flex items-center justify-center text-gray-400 hover:text-lime-400 hover:border-lime-400/30 transition" aria-label="Instagram"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zM12 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg></a>
                        <a href="https://twitter.com/libyamarket" target="_blank" rel="noopener" class="w-9 h-9 rounded-lg bg-gray-900 border border-gray-800 flex items-center justify-center text-gray-400 hover:text-lime-400 hover:border-lime-400/30 transition" aria-label="Twitter"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg></a>
                    </div>
                </div>
                <div>
                    <h3 class="text-white font-black text-sm uppercase tracking-widest mb-4">Explore</h3>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ route('shop.products.index') }}" class="text-gray-400 hover:text-lime-400">Shop</a></li>
                        <li><a href="{{ route('shop.about') }}" class="text-gray-400 hover:text-lime-400">About us</a></li>
                        <li><a href="{{ route('shop.contact') }}" class="text-gray-400 hover:text-lime-400">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-white font-black text-sm uppercase tracking-widest mb-4">Sell with us</h3>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ route('register') }}" class="text-gray-400 hover:text-lime-400">Create account</a></li>
                        @auth
                            @if (! auth()->user()->vendor)
                                <li><a href="{{ route('vendor.register') }}" class="text-gray-400 hover:text-lime-400">Open a store</a></li>
                            @endif
                        @endauth
                    </ul>
                </div>
            </div>
            <div class="pt-8 border-t border-gray-800 flex flex-col sm:flex-row justify-between items-center gap-3 text-xs text-gray-500">
                <p>&copy; {{ date('Y') }} Libya Marketplace. All rights reserved.</p>
                <p>Made in Libya with <span class="text-lime-400">&hearts;</span></p>
            </div>
        </div>
    </footer>
</body>
</html>