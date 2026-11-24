<x-layouts.shop :title="'My Orders'">
    <div class="mb-8">
        <h1 class="text-3xl sm:text-4xl font-black text-white">My orders</h1>
        <p class="text-sm text-gray-500 mt-1">{{ $orders->total() }} order{{ $orders->total() === 1 ? '' : 's' }}</p>
    </div>

    @if ($orders->isEmpty())
        <div class="bg-[#171717] border border-gray-800 rounded-2xl p-16 text-center">
            <div class="w-20 h-20 rounded-full bg-gray-900 flex items-center justify-center mx-auto mb-5">
                <svg class="w-10 h-10 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            </div>
            <h3 class="text-xl font-black text-white mb-2">No orders yet</h3>
            <p class="text-sm text-gray-500 mb-6">Start shopping to see your orders here</p>
            <a href="{{ route('shop.products.index') }}" class="inline-block px-8 py-3.5 bg-lime-400 text-black font-black rounded-xl hover:bg-lime-300">Browse products</a>
        </div>
    @else
        <div class="space-y-3">
            @foreach ($orders as $order)
                @php
                    $pct = match($order->status->value) {
                        'pending', 'pending_customer_approval' => 15,
                        'confirmed' => 40,
                        'shipped' => 70,
                        'delivered' => 100,
                        'cancelled' => 0,
                        default => 0,
                    };
                @endphp
                <a href="{{ route('shop.orders.show', $order) }}" class="block bg-[#171717] border {{ $order->status->value === 'pending_customer_approval' ? 'border-purple-500/40' : 'border-gray-800' }} rounded-2xl p-5 hover:border-lime-400/30 hover:-translate-y-0.5 transition">
                    <div class="flex items-start justify-between gap-3 flex-wrap">
                        <div class="min-w-0 flex-1">
                            <div class="font-mono font-black text-white text-base">{{ $order->order_number }}</div>
                            <div class="text-xs text-gray-500 mt-0.5">{{ $order->created_at->format('M d, Y · H:i') }} &middot; {{ $order->vendor->name }}</div>
                            <div class="text-sm text-gray-400 mt-2">{{ $order->items->count() }} item{{ $order->items->count() === 1 ? '' : 's' }}</div>
                        </div>
                        <div class="text-right shrink-0">
                            <span class="inline-block px-2 py-0.5 text-[10px] font-black uppercase tracking-wider rounded {{ $order->status->badgeClass() }}">{{ $order->status->label() }}</span>
                            <div class="font-black text-lime-400 text-lg mt-1.5">{{ number_format((float) $order->total, 2) }} <span class="text-xs text-gray-500">LYD</span></div>
                        </div>
                    </div>

                    @if ($pct > 0)
                        <div class="mt-4 h-1 bg-gray-800 rounded-full overflow-hidden">
                            <div class="h-full bg-lime-400 transition-all" style="width: {{ $pct }}%"></div>
                        </div>
                    @endif
                </a>
            @endforeach
        </div>
        <div class="mt-8">{{ $orders->links() }}</div>
    @endif
</x-layouts.shop>