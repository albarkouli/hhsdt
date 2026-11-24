<x-layouts.dashboard :title="'Super Admin'">
    @php
        $pendingVendors  = \App\Models\Vendor::where('status', 'pending')->count();
        $activeVendors   = \App\Models\Vendor::where('status', 'active')->count();
        $categoriesCount = \App\Models\Category::count();
        $newMessages     = \App\Models\ContactMessage::where('status', 'new')->count();
        $totalReviews    = \App\Models\Review::count();
        $hiddenReviews   = \App\Models\Review::where('is_published', false)->count();
        $totalCoupons    = \App\Models\Coupon::count();
        $activeCoupons   = \App\Models\Coupon::active()->count();
        $totalSales      = \App\Models\Order::where('status', '!=', 'cancelled')->sum('total');
        $ordersCount     = \App\Models\Order::where('status', '!=', 'cancelled')->count();
        $avgOrder        = $ordersCount > 0 ? $totalSales / $ordersCount : 0;
        $totalEarnings   = \App\Models\PlatformEarning::sum('amount');
        $topVendors      = \App\Models\Vendor::query()
            ->withSum(['orders as total_sales' => fn ($q) => $q->where('status', '!=', 'cancelled')], 'total')
            ->orderByDesc('total_sales')->limit(5)->get(['id', 'name']);
    @endphp

    <div class="space-y-6">
        <div>
            <h1 class="text-3xl font-black text-[#D6DEE6]">Admin Dashboard</h1>
            <p class="text-sm text-[#A8B5C1] mt-1">Welcome, {{ auth()->user()->name }}</p>
        </div>

        {{-- Financial --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-gradient-to-br from-gold to-gold-hover rounded-2xl p-5">
                <div class="text-[10px] font-black uppercase tracking-widest text-[#D6DEE6]/70 mb-2">Total Sales</div>
                <div class="text-3xl font-black text-[#D6DEE6]">{{ number_format((float) $totalSales, 2) }}</div>
                <div class="text-xs text-[#D6DEE6]/70 font-bold mt-1">LYD (excl. cancelled)</div>
            </div>
            <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl p-5">
                <div class="text-[10px] font-black uppercase tracking-widest text-[#D6DEE6]/80 mb-2">Platform Earnings</div>
                <div class="text-3xl font-black text-[#D6DEE6]">{{ number_format((float) $totalEarnings, 2) }}</div>
                <div class="text-xs text-[#D6DEE6]/80 font-bold mt-1">LYD commissions</div>
            </div>
            <div class="bg-[#171717] border border-[#344453] rounded-2xl p-5">
                <div class="text-[10px] font-black uppercase tracking-widest text-[#A8B5C1] mb-2">Orders</div>
                <div class="text-3xl font-black text-[#D6DEE6]">{{ $ordersCount }}</div>
                <div class="text-xs text-[#A8B5C1] font-bold mt-1">Active + completed</div>
            </div>
            <div class="bg-[#171717] border border-[#344453] rounded-2xl p-5">
                <div class="text-[10px] font-black uppercase tracking-widest text-[#A8B5C1] mb-2">Avg Order</div>
                <div class="text-3xl font-black text-[#D6DEE6]">{{ number_format((float) $avgOrder, 2) }}</div>
                <div class="text-xs text-[#A8B5C1] font-bold mt-1">LYD per order</div>
            </div>
        </div>

        {{-- Management cards --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-3">
            <a href="{{ route('admin.vendors.index', ['status' => 'pending']) }}" class="bg-[#171717] border border-[#344453] rounded-xl p-4 hover:border-yellow-500/30 transition">
                <div class="text-[10px] font-black uppercase tracking-widest text-[#A8B5C1]">Pending</div>
                <div class="text-2xl font-black text-yellow-400">{{ $pendingVendors }}</div>
                <div class="text-[10px] text-[#A8B5C1] mt-1">vendors</div>
            </a>
            <a href="{{ route('admin.vendors.index', ['status' => 'active']) }}" class="bg-[#171717] border border-[#344453] rounded-xl p-4 hover:border-gold/30 transition">
                <div class="text-[10px] font-black uppercase tracking-widest text-[#A8B5C1]">Active</div>
                <div class="text-2xl font-black text-gold">{{ $activeVendors }}</div>
                <div class="text-[10px] text-[#A8B5C1] mt-1">vendors</div>
            </a>
            <a href="{{ route('admin.categories.index') }}" class="bg-[#171717] border border-[#344453] rounded-xl p-4 hover:border-indigo-500/30 transition">
                <div class="text-[10px] font-black uppercase tracking-widest text-[#A8B5C1]">Categories</div>
                <div class="text-2xl font-black text-indigo-400">{{ $categoriesCount }}</div>
            </a>
            <a href="{{ route('admin.orders.index') }}" class="bg-[#171717] border border-[#344453] rounded-xl p-4 hover:border-gold/30 transition">
                <div class="text-[10px] font-black uppercase tracking-widest text-[#A8B5C1]">Orders</div>
                <div class="text-2xl font-black text-gold">{{ $ordersCount }}</div>
            </a>
            <a href="{{ route('admin.reviews.index') }}" class="bg-[#171717] border border-[#344453] rounded-xl p-4 hover:border-orange-500/30 transition">
                <div class="text-[10px] font-black uppercase tracking-widest text-[#A8B5C1]">Reviews</div>
                <div class="text-2xl font-black text-orange-400">{{ $totalReviews }}</div>
                @if ($hiddenReviews > 0)<div class="text-[10px] text-red-400 font-bold mt-1">{{ $hiddenReviews }} hidden</div>@endif
            </a>
            <a href="{{ route('admin.coupons.index') }}" class="bg-[#171717] border border-[#344453] rounded-xl p-4 hover:border-teal-500/30 transition">
                <div class="text-[10px] font-black uppercase tracking-widest text-[#A8B5C1]">Coupons</div>
                <div class="text-2xl font-black text-teal-400">{{ $totalCoupons }}</div>
                <div class="text-[10px] text-[#A8B5C1] mt-1">{{ $activeCoupons }} active</div>
            </a>
            <a href="{{ route('admin.messages.index') }}" class="bg-[#171717] border border-[#344453] rounded-xl p-4 hover:border-purple-500/30 transition">
                <div class="text-[10px] font-black uppercase tracking-widest text-[#A8B5C1]">Messages</div>
                <div class="text-2xl font-black text-purple-400">{{ $newMessages }}</div>
                @if ($newMessages > 0)<div class="text-[10px] text-purple-400 font-bold mt-1">new</div>@endif
            </a>
        </div>
        @if ($topVendors->isNotEmpty() && $topVendors->first()->total_sales > 0)
            <div class="bg-[#171717] border border-[#344453] rounded-2xl p-6">
                <h2 class="font-black text-[#D6DEE6] mb-4">Top vendors by sales</h2>
                <ol class="space-y-2">
                    @foreach ($topVendors as $i => $v)
                        <li class="flex items-center justify-between text-sm py-2 border-b border-[#344453] last:border-0">
                            <div class="flex items-center gap-3">
                                <span class="w-7 h-7 rounded-full bg-gold text-[#D6DEE6] flex items-center justify-center text-xs font-black">{{ $i + 1 }}</span>
                                <span class="font-bold text-[#D6DEE6]">{{ $v->name }}</span>
                            </div>
                            <span class="font-black text-gold">{{ number_format((float) $v->total_sales, 2) }} <span class="text-[10px] text-[#A8B5C1]">LYD</span></span>
                        </li>
                    @endforeach
                </ol>
            </div>
        @endif

        <div class="bg-[#171717] border border-[#344453] rounded-2xl p-5 flex flex-wrap gap-2">
            <a href="{{ route('admin.vendors.index') }}" class="px-4 py-2.5 bg-gold text-[#D6DEE6] font-black rounded-lg text-sm hover:bg-gold-hover">Vendors</a>
            <a href="{{ route('admin.orders.index') }}" class="px-4 py-2.5 bg-indigo-500 text-[#D6DEE6] font-black rounded-lg text-sm hover:bg-indigo-400">Orders</a>
            <a href="{{ route('admin.categories.index') }}" class="px-4 py-2.5 bg-purple-500 text-[#D6DEE6] font-black rounded-lg text-sm hover:bg-purple-400">Categories</a>
            <a href="{{ route('admin.coupons.index') }}" class="px-4 py-2.5 bg-teal-500 text-[#D6DEE6] font-black rounded-lg text-sm hover:bg-teal-400">Coupons</a>
            <a href="{{ route('admin.earnings.index') }}" class="px-4 py-2.5 bg-emerald-500 text-[#D6DEE6] font-black rounded-lg text-sm hover:bg-emerald-400">Earnings</a>
            <a href="{{ route('admin.commissions.index') }}" class="px-4 py-2.5 bg-teal-500 text-[#D6DEE6] font-black rounded-lg text-sm hover:bg-teal-400">Commissions</a>
            <a href="{{ route('admin.reviews.index') }}" class="px-4 py-2.5 bg-orange-500 text-[#D6DEE6] font-black rounded-lg text-sm hover:bg-orange-400">Reviews</a>
            <a href="{{ route('admin.messages.index') }}" class="px-4 py-2.5 bg-pink-500 text-[#D6DEE6] font-black rounded-lg text-sm hover:bg-pink-400">Messages</a>
        </div>
    </div>
</x-layouts.dashboard>