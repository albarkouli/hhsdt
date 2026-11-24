<x-layouts.dashboard :title="'Vendor Dashboard'">
    <div class="space-y-6">
        {{-- Header --}}
        <div class="bg-[#171717] border border-[#344453] rounded-2xl p-6">
            <div class="flex items-start justify-between flex-wrap gap-3">
                <div class="flex items-center gap-4">
                    @if ($vendor->logo)
                        <img src="{{ Storage::url($vendor->logo) }}" alt="{{ $vendor->name }}" class="w-14 h-14 rounded-xl object-cover">
                    @else
                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-gold to-gold-hover flex items-center justify-center text-[#D6DEE6] text-2xl font-black">{{ Str::substr($vendor->name, 0, 1) }}</div>
                    @endif
                    <div>
                        <h1 class="text-2xl font-black text-[#D6DEE6]">{{ $vendor->name }}</h1>
                        <p class="text-xs text-[#A8B5C1] mt-0.5">/{{ $vendor->slug }}</p>
                    </div>
                </div>
                <span @class([
                    'px-3 py-1.5 text-[10px] font-black uppercase tracking-widest rounded-md border',
                    'bg-gold/10 text-gold border-gold/30' => $vendor->status->canSell(),
                    'bg-yellow-500/10 text-yellow-400 border-yellow-500/30' => $vendor->status->value === 'pending',
                    'bg-red-500/10 text-red-400 border-red-500/30' => in_array($vendor->status->value, ['suspended', 'rejected']),
                ])>
                    {{ $vendor->status->label() }}
                </span>
            </div>
            @unless ($vendor->status->canSell())
                <div class="mt-4 rounded-lg border border-yellow-500/30 bg-yellow-500/10 p-3 text-sm text-yellow-300 flex items-start gap-2">
                    <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5 19h14a2 2 0 001.84-2.75L13.74 4a2 2 0 00-3.5 0L3.34 16.25A2 2 0 005 19z"/></svg>
                    Your store is not active yet. You cannot publish products until approved.
                </div>
            @endunless
        </div>

        {{-- Main Stats --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-gradient-to-br from-gold to-gold-hover rounded-2xl p-5">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-black uppercase tracking-widest text-[#D6DEE6]/70">Total revenue</span>
                    <svg class="w-5 h-5 text-[#D6DEE6]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="text-3xl font-black text-[#D6DEE6]">{{ number_format($totalRevenue, 2) }}</div>
                <div class="text-xs text-[#D6DEE6]/70 font-bold mt-1">LYD</div>
            </div>

            <div class="bg-[#171717] border border-[#344453] rounded-2xl p-5">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-black uppercase tracking-widest text-[#A8B5C1]">Total orders</span>
                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
                <div class="text-3xl font-black text-[#D6DEE6]">{{ $totalOrders }}</div>
                @if ($pendingOrders > 0)
                    <div class="text-xs text-orange-400 font-bold mt-1">{{ $pendingOrders }} pending</div>
                @else
                    <div class="text-xs text-emerald-400 font-bold mt-1">All handled</div>
                @endif
            </div>

            <div class="bg-[#171717] border border-[#344453] rounded-2xl p-5">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-black uppercase tracking-widest text-[#A8B5C1]">Products</span>
                    <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <div class="text-3xl font-black text-[#D6DEE6]">{{ $totalProducts }}</div>
                <div class="text-xs text-[#A8B5C1] font-bold mt-1">{{ $activeProducts }} active</div>
            </div>

            <div class="bg-[#171717] border border-[#344453] rounded-2xl p-5">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-black uppercase tracking-widest text-[#A8B5C1]">Delivered revenue</span>
                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="text-3xl font-black text-[#D6DEE6]">{{ number_format($deliveredRevenue, 2) }}</div>
                <div class="text-xs text-[#A8B5C1] font-bold mt-1">LYD received</div>
            </div>
        </div>
        {{-- Charts --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div class="lg:col-span-2 bg-[#171717] border border-[#344453] rounded-2xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="font-black text-[#D6DEE6]">Sales أ¢â‚¬â€‌ last 7 days</h2>
                        <p class="text-xs text-[#A8B5C1]">Revenue in LYD</p>
                    </div>
                </div>
                <div class="h-64"><canvas id="salesChart"></canvas></div>
            </div>

            <div class="bg-[#171717] border border-[#344453] rounded-2xl p-6">
                <h2 class="font-black text-[#D6DEE6] mb-4">Order status</h2>
                <div class="h-64"><canvas id="statusChart"></canvas></div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <a href="{{ route('vendor.products.index') }}" class="bg-[#171717] border border-[#344453] rounded-xl p-4 hover:border-gold/30 hover:-translate-y-1 transition text-center">
                <svg class="w-6 h-6 mx-auto mb-2 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                <div class="text-xs font-bold text-[#D6DEE6]">Products</div>
            </a>
            <a href="{{ route('vendor.orders.index') }}" class="bg-[#171717] border border-[#344453] rounded-xl p-4 hover:border-gold/30 hover:-translate-y-1 transition text-center">
                <svg class="w-6 h-6 mx-auto mb-2 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                <div class="text-xs font-bold text-[#D6DEE6]">Orders</div>
            </a>
            <a href="{{ route('vendor.settings.edit') }}" class="bg-[#171717] border border-[#344453] rounded-xl p-4 hover:border-gold/30 hover:-translate-y-1 transition text-center">
                <svg class="w-6 h-6 mx-auto mb-2 text-[#A8B5C1]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <div class="text-xs font-bold text-[#D6DEE6]">Settings</div>
            </a>
            <a href="{{ route('shop.stores.show', ['vendor' => $vendor->slug]) }}" class="bg-[#171717] border border-[#344453] rounded-xl p-4 hover:border-gold/30 hover:-translate-y-1 transition text-center">
                <svg class="w-6 h-6 mx-auto mb-2 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                <div class="text-xs font-bold text-[#D6DEE6]">View store</div>
            </a>
        </div>
        {{-- Two Columns --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            {{-- Recent Orders --}}
            <div class="bg-[#171717] border border-[#344453] rounded-2xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-black text-[#D6DEE6]">Recent orders</h2>
                    <a href="{{ route('vendor.orders.index') }}" class="text-xs font-bold text-gold hover:text-gold-hover">View all &rarr;</a>
                </div>
                @if ($recentOrders->isEmpty())
                    <p class="text-sm text-[#A8B5C1] py-6 text-center">No orders yet</p>
                @else
                    <ul class="space-y-2">
                        @foreach ($recentOrders as $order)
                            <li class="p-3 rounded-lg bg-[#263543] border border-[#344453] hover:border-gold/30 transition">
                                <div class="flex items-center justify-between gap-3">
                                    <div class="min-w-0 flex-1">
                                        <a href="{{ route('vendor.orders.show', $order) }}" class="font-mono font-bold text-[#D6DEE6] text-sm hover:text-gold">{{ $order->order_number }}</a>
                                        <div class="text-xs text-[#A8B5C1]">{{ $order->user?->name }} &middot; {{ $order->created_at->diffForHumans() }}</div>
                                    </div>
                                    <div class="text-right shrink-0">
                                        <div class="font-black text-gold text-sm">{{ number_format((float) $order->total, 2) }} <span class="text-[10px] text-[#A8B5C1]">LYD</span></div>
                                        <span class="px-1.5 py-0.5 text-[10px] font-black uppercase tracking-wider rounded {{ $order->status->badgeClass() }}">{{ $order->status->label() }}</span>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            {{-- Top Products --}}
            <div class="bg-[#171717] border border-[#344453] rounded-2xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-black text-[#D6DEE6]">Top products</h2>
                    <a href="{{ route('vendor.products.index') }}" class="text-xs font-bold text-gold hover:text-gold-hover">View all &rarr;</a>
                </div>
                @if ($topProducts->isEmpty())
                    <p class="text-sm text-[#A8B5C1] py-6 text-center">No sales yet</p>
                @else
                    <ol class="space-y-3">
                        @foreach ($topProducts as $i => $item)
                            <li class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gold text-[#D6DEE6] flex items-center justify-center text-sm font-black shrink-0">{{ $i + 1 }}</div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-bold text-[#D6DEE6] truncate">{{ $item->product_name }}</div>
                                    <div class="text-xs text-[#A8B5C1]">{{ $item->units_sold }} units sold</div>
                                </div>
                                <div class="text-sm font-black text-gold shrink-0">{{ number_format((float) $item->revenue, 2) }} <span class="text-[10px] text-[#A8B5C1]">LYD</span></div>
                            </li>
                        @endforeach
                    </ol>
                @endif
            </div>
        </div>

        {{-- Low Stock --}}
        @if ($lowStockProducts->isNotEmpty())
            <div class="bg-[#171717] border border-orange-500/30 rounded-2xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <h2 class="font-black text-[#D6DEE6]">Low stock alerts</h2>
                    </div>
                    <a href="{{ route('vendor.products.index') }}" class="text-xs font-bold text-gold hover:text-gold-hover">Manage &rarr;</a>
                </div>
                <ul class="space-y-2">
                    @foreach ($lowStockProducts as $p)
                        <li class="flex justify-between items-center text-sm py-1.5 border-b border-[#344453] last:border-0">
                            <span class="text-[#D6DEE6]">{{ $p->name }}</span>
                            <span @class([
                                'px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider',
                                'bg-red-500/10 text-red-400 border border-red-500/30' => $p->stock === 0,
                                'bg-orange-500/10 text-orange-400 border border-orange-500/30' => $p->stock > 0,
                            ])>{{ $p->stock === 0 ? 'Out of stock' : $p->stock . ' left' }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const salesCtx = document.getElementById('salesChart');
            if (salesCtx) {
                new Chart(salesCtx, {
                    type: 'line',
                    data: {
                        labels: @json($chartLabels),
                        datasets: [{
                            label: 'Revenue',
                            data: @json($chartRevenue),
                            borderColor: '#a3e635',
                            backgroundColor: 'rgba(163, 230, 53, 0.1)',
                            tension: 0.35,
                            fill: true,
                            pointBackgroundColor: '#a3e635',
                            pointBorderColor: '#0a0a0a',
                            pointRadius: 4,
                            pointHoverRadius: 6,
                        }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: { legend: { display: false }, tooltip: { backgroundColor: '#171717', titleColor: '#a3e635', bodyColor: '#fafafa', borderColor: '#262626', borderWidth: 1, padding: 10, displayColors: false } },
                        scales: {
                            y: { beginAtZero: true, grid: { color: 'rgba(38, 38, 38, 0.5)' }, ticks: { color: '#737373', font: { size: 11 } } },
                            x: { grid: { display: false }, ticks: { color: '#737373', font: { size: 11 } } }
                        }
                    }
                });
            }

            const statusCtx = document.getElementById('statusChart');
            if (statusCtx) {
                new Chart(statusCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Pending', 'Confirmed', 'Shipped', 'Delivered', 'Cancelled'],
                        datasets: [{
                            data: @json(array_values($orderStatusBreakdown)),
                            backgroundColor: ['#facc15', '#3b82f6', '#a3e635', '#10b981', '#ef4444'],
                            borderWidth: 0,
                        }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: { legend: { position: 'bottom', labels: { color: '#a3a3a3', boxWidth: 10, padding: 8, font: { size: 10 } } } },
                        cutout: '70%',
                    }
                });
            }
        });
    </script>
</x-layouts.dashboard>