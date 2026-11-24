<x-layouts.dashboard :title="'Customer Dashboard'">
    @php
        $ordersCount = auth()->user()->orders()->count();
        $pendingOrders = auth()->user()->orders()->where('status', 'pending')->count();
        $wishlistCount = auth()->user()->wishlist()->count();
        $recentOrders = auth()->user()->orders()->with(['vendor:id,name'])->latest()->limit(4)->get();
    @endphp

    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-end justify-between">
            <div>
                <h1 class="text-3xl sm:text-4xl font-black text-[#D6DEE6]">Hi, {{ Str::limit(auth()->user()->name, 20) }}</h1>
                <p class="text-sm text-[#A8B5C1] mt-1">Welcome back to your dashboard</p>
            </div>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <a href="{{ route('shop.orders.index') }}" class="bg-[#171717] border border-[#344453] rounded-2xl p-6 hover:border-[#344453] transition">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-lg bg-indigo-500/10 border border-indigo-500/30 flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    </div>
                    @if ($pendingOrders > 0)
                        <span class="text-[10px] font-black text-orange-400 bg-orange-500/10 border border-orange-500/30 px-2 py-1 rounded">{{ $pendingOrders }} pending</span>
                    @endif
                </div>
                <div class="text-3xl font-black text-[#D6DEE6]">{{ $ordersCount }}</div>
                <div class="text-xs text-[#A8B5C1] mt-1">Total orders</div>
            </a>

            <a href="{{ route('shop.wishlist.index') }}" class="bg-[#171717] border border-[#344453] rounded-2xl p-6 hover:border-[#344453] transition">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-lg bg-red-500/10 border border-red-500/30 flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/></svg>
                    </div>
                </div>
                <div class="text-3xl font-black text-[#D6DEE6]">{{ $wishlistCount }}</div>
                <div class="text-xs text-[#A8B5C1] mt-1">Wishlist items</div>
            </a>

            <a href="{{ route('shop.products.index') }}" class="bg-gradient-to-br from-gold to-gold-hover rounded-2xl p-6 hover:opacity-90 transition">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-lg bg-[#17212B]/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-[#D6DEE6]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    </div>
                </div>
                <div class="text-3xl font-black text-[#D6DEE6]">Shop &rarr;</div>
                <div class="text-xs text-[#D6DEE6]/70 font-bold mt-1">Browse products</div>
            </a>
        </div>

        {{-- Recent Orders --}}
        @if ($recentOrders->isNotEmpty())
            <div class="bg-[#171717] border border-[#344453] rounded-2xl p-6">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="font-black text-[#D6DEE6] text-lg">Recent orders</h2>
                    <a href="{{ route('shop.orders.index') }}" class="text-xs font-bold text-gold hover:text-gold-hover">View all &rarr;</a>
                </div>
                <div class="space-y-2">
                    @foreach ($recentOrders as $order)
                        <a href="{{ route('shop.orders.show', $order) }}" class="flex items-center justify-between p-3 rounded-lg bg-[#263543] border border-[#344453] hover:border-gold/30 transition">
                            <div class="flex-1 min-w-0">
                                <div class="font-mono font-bold text-[#D6DEE6] text-sm">{{ $order->order_number }}</div>
                                <div class="text-xs text-[#A8B5C1]">{{ $order->vendor->name }} &middot; {{ $order->created_at->format('M d') }}</div>
                            </div>
                            <div class="flex items-center gap-3 shrink-0">
                                <span class="px-2 py-0.5 text-[10px] font-black uppercase tracking-wider rounded {{ $order->status->badgeClass() }}">{{ $order->status->label() }}</span>
                                <span class="font-black text-gold text-sm">{{ number_format((float) $order->total, 2) }} <span class="text-[10px] text-[#A8B5C1]">LYD</span></span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Become a vendor --}}
        @unless (auth()->user()->vendor()->exists())
            <div class="bg-gradient-to-br from-gold to-gold-hover rounded-2xl p-8 relative overflow-hidden">
                <div class="absolute -top-20 -right-20 w-64 h-64 rounded-full bg-[#263543]/20 blur-3xl"></div>
                <div class="relative flex items-start justify-between gap-4 flex-wrap">
                    <div class="flex-1 min-w-[250px]">
                        <h2 class="text-2xl font-black text-[#D6DEE6] mb-2">Have products to sell?</h2>
                        <p class="text-sm text-[#D6DEE6]/70 max-w-md">Open your own store on WESALKOM and reach customers across Libya. Zero setup fees.</p>
                    </div>
                    <a href="{{ route('vendor.register') }}" class="shrink-0 px-6 py-3 bg-[#17212B] text-[#D6DEE6] font-black rounded-lg hover:bg-[#263543]">Open your store &rarr;</a>
                </div>
            </div>
        @endunless
    </div>
</x-layouts.dashboard>