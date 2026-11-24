<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <meta name="theme-color" content="#0a0a0a">
    <title>{{ $title ?? 'Dashboard' }} - Libya Marketplace</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Cairo:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#0a0a0a] text-gray-100 antialiased">
    <nav class="bg-[#0a0a0a]/95 backdrop-blur-md border-b border-gray-800 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-6">
            <button onclick="document.getElementById('dash-mobile-panel').classList.toggle('hidden')" class="md:hidden p-2 rounded-lg text-gray-300 hover:bg-gray-900 shrink-0" aria-label="Menu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
                    <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                        <div class="w-8 h-8 rounded-lg bg-lime-400 flex items-center justify-center transition-transform group-hover:rotate-6">
                            <svg class="w-4 h-4 text-black" fill="currentColor" viewBox="0 0 20 20"><path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3z"/></svg>
                        </div>
                        <span class="text-sm font-black tracking-tight text-white">Libya<span class="text-lime-400">.</span>Market</span>
                    </a>

                    @auth
                        @if (auth()->user()->hasRole('super_admin'))
                            <div class="hidden md:flex items-center gap-0.5">
                                <a href="{{ route('admin.dashboard') }}" class="px-2.5 py-1.5 rounded-md text-xs font-semibold text-gray-300 hover:bg-gray-900 hover:text-lime-400">Dashboard</a>
                                <a href="{{ route('admin.vendors.index') }}" class="px-2.5 py-1.5 rounded-md text-xs font-semibold text-gray-300 hover:bg-gray-900 hover:text-lime-400">Vendors</a>
                                <a href="{{ route('admin.orders.index') }}" class="px-2.5 py-1.5 rounded-md text-xs font-semibold text-gray-300 hover:bg-gray-900 hover:text-lime-400">Orders</a>
                                <a href="{{ route('admin.categories.index') }}" class="px-2.5 py-1.5 rounded-md text-xs font-semibold text-gray-300 hover:bg-gray-900 hover:text-lime-400">Categories</a>
                                <a href="{{ route('admin.reviews.index') }}" class="px-2.5 py-1.5 rounded-md text-xs font-semibold text-gray-300 hover:bg-gray-900 hover:text-lime-400">Reviews</a>
                                <a href="{{ route('admin.coupons.index') }}" class="px-2.5 py-1.5 rounded-md text-xs font-semibold text-gray-300 hover:bg-gray-900 hover:text-lime-400">Coupons</a>
                                <a href="{{ route('admin.earnings.index') }}" class="px-2.5 py-1.5 rounded-md text-xs font-semibold text-gray-300 hover:bg-gray-900 hover:text-lime-400">Earnings</a>
                                <a href="{{ route('admin.commissions.index') }}" class="px-2 py-1 text-sm text-gray-300 hover:bg-gray-900 hover:text-lime-400">Commissions</a>
                                <a href="{{ route('admin.messages.index') }}" class="px-2.5 py-1.5 rounded-md text-xs font-semibold text-gray-300 hover:bg-gray-900 hover:text-lime-400">Messages</a>
                            </div>
                        @endif
                        @if (auth()->user()->hasRole('vendor') && auth()->user()->vendor)
                            <div class="hidden md:flex items-center gap-0.5">
                                <a href="{{ route('vendor.dashboard') }}" class="px-2.5 py-1.5 rounded-md text-xs font-semibold text-gray-300 hover:bg-gray-900 hover:text-lime-400">Dashboard</a>
                                <a href="{{ route('vendor.products.index') }}" class="px-2.5 py-1.5 rounded-md text-xs font-semibold text-gray-300 hover:bg-gray-900 hover:text-lime-400">Products</a>
                                <a href="{{ route('vendor.orders.index') }}" class="px-2.5 py-1.5 rounded-md text-xs font-semibold text-gray-300 hover:bg-gray-900 hover:text-lime-400">Orders</a>
                                <a href="{{ route('vendor.settings.edit') }}" class="px-2.5 py-1.5 rounded-md text-xs font-semibold text-gray-300 hover:bg-gray-900 hover:text-lime-400">Settings</a>
                            </div>
                        @endif
                    @endauth

                    @php $roleName = auth()->user()->getRoleNames()->first() ?? 'no-role'; @endphp
                    <span class="hidden sm:inline px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-widest bg-lime-400/10 text-lime-400 border border-lime-400/30">
                        {{ str_replace('_', ' ', $roleName) }}
                    </span>
                </div>

                <div class="flex items-center gap-2">
                    @auth
                        @php $unread = auth()->user()->unreadNotifications->count(); @endphp
                        <a href="{{ route('notifications.index') }}" class="relative p-2 rounded-lg text-gray-300 hover:bg-gray-900 hover:text-lime-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            @if ($unread > 0)<span class="absolute top-0.5 right-0.5 bg-red-500 text-white text-[9px] font-black rounded-full min-w-[16px] h-4 flex items-center justify-center px-1">{{ $unread > 99 ? '99+' : $unread }}</span>@endif
                        </a>
                        <div class="flex items-center gap-2 pl-2 pr-1 py-1">
                            <div class="w-8 h-8 rounded-full bg-lime-400 text-black flex items-center justify-center text-xs font-black">{{ Str::substr(auth()->user()->name, 0, 1) }}</div>
                            <span class="hidden sm:block text-sm font-semibold text-white">{{ Str::limit(auth()->user()->name, 15) }}</span>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-xs font-semibold text-gray-400 hover:text-red-400 px-2 py-1.5 rounded-md hover:bg-red-500/10">Logout</button>
                        </form>
                    @endauth
                </div>
            </div>
        </div>
        <div id="dash-mobile-panel" class="hidden md:hidden bg-[#0a0a0a] border-t border-gray-800">
        <div class="px-4 py-3 space-y-1">
            @auth
                @if (auth()->user()->hasRole('super_admin'))
                    <div class="px-2 py-1 text-[10px] font-black uppercase tracking-widest text-lime-400">Admin</div>
                    <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2.5 text-sm text-gray-300 hover:bg-gray-900 hover:text-lime-400 rounded-md">Dashboard</a>
                    <a href="{{ route('admin.vendors.index') }}" class="block px-3 py-2.5 text-sm text-gray-300 hover:bg-gray-900 hover:text-lime-400 rounded-md">Vendors</a>
                    <a href="{{ route('admin.orders.index') }}" class="block px-3 py-2.5 text-sm text-gray-300 hover:bg-gray-900 hover:text-lime-400 rounded-md">Orders</a>
                    <a href="{{ route('admin.categories.index') }}" class="block px-3 py-2.5 text-sm text-gray-300 hover:bg-gray-900 hover:text-lime-400 rounded-md">Categories</a>
                    <a href="{{ route('admin.reviews.index') }}" class="block px-3 py-2.5 text-sm text-gray-300 hover:bg-gray-900 hover:text-lime-400 rounded-md">Reviews</a>
                    <a href="{{ route('admin.coupons.index') }}" class="block px-3 py-2.5 text-sm text-gray-300 hover:bg-gray-900 hover:text-lime-400 rounded-md">Coupons</a>
                    <a href="{{ route('admin.earnings.index') }}" class="block px-3 py-2.5 text-sm text-gray-300 hover:bg-gray-900 hover:text-lime-400 rounded-md">Earnings</a>
                    <a href="{{ route('admin.messages.index') }}" class="block px-3 py-2.5 text-sm text-gray-300 hover:bg-gray-900 hover:text-lime-400 rounded-md">Messages</a>
                @endif
                @if (auth()->user()->hasRole('vendor') && auth()->user()->vendor)
                    <div class="px-2 py-1 mt-2 text-[10px] font-black uppercase tracking-widest text-lime-400">Vendor</div>
                    <a href="{{ route('vendor.dashboard') }}" class="block px-3 py-2.5 text-sm text-gray-300 hover:bg-gray-900 hover:text-lime-400 rounded-md">Dashboard</a>
                    <a href="{{ route('vendor.products.index') }}" class="block px-3 py-2.5 text-sm text-gray-300 hover:bg-gray-900 hover:text-lime-400 rounded-md">Products</a>
                    <a href="{{ route('vendor.orders.index') }}" class="block px-3 py-2.5 text-sm text-gray-300 hover:bg-gray-900 hover:text-lime-400 rounded-md">Orders</a>
                    <a href="{{ route('vendor.settings.edit') }}" class="block px-3 py-2.5 text-sm text-gray-300 hover:bg-gray-900 hover:text-lime-400 rounded-md">Settings</a>
                @endif
            @endauth
        </div>
    </div>
</nav>

    <main class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        {{ $slot }}
    </main>
</body>
</html>