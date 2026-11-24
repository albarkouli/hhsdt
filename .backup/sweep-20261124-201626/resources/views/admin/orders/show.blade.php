<x-layouts.dashboard :title="$order->order_number">
    <div class="space-y-6">
        <div class="flex items-start justify-between flex-wrap gap-3">
            <div>
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-gray-500 hover:text-lime-400">&larr; All orders</a>
                <h1 class="text-3xl font-black text-white mt-1 font-mono">{{ $order->order_number }}</h1>
                <p class="text-sm text-gray-500 mt-1">{{ $order->created_at->format('Y-m-d H:i') }} · {{ $order->vendor->name }}</p>
            </div>
            <span class="px-3 py-1.5 text-xs font-black uppercase tracking-widest rounded-md {{ $order->status->badgeClass() }}">{{ $order->status->label() }}</span>
        </div>

        @if (session('status'))
            <div class="rounded-xl border border-lime-400/30 bg-lime-400/10 p-3 text-sm text-lime-300">{{ session('status') }}</div>
        @endif
        @if (session('error'))
            <div class="rounded-xl border border-red-500/30 bg-red-500/10 p-3 text-sm text-red-300">{{ session('error') }}</div>
        @endif

        @if ($order->isCancellable())
            <div class="bg-[#171717] border border-gray-800 rounded-xl p-4">
                <form method="POST" action="{{ route('admin.orders.cancel', $order) }}" onsubmit="return confirm('Cancel this order? Stock will be restored.')">
                    @csrf
                    <button class="px-4 py-2 bg-red-500/20 border border-red-500/30 text-red-400 hover:bg-red-500/30 font-black rounded-lg text-sm">Cancel order (admin)</button>
                </form>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div class="lg:col-span-2 space-y-4">
                <div class="bg-[#171717] border border-gray-800 rounded-2xl p-5">
                    <h2 class="font-black text-white mb-3">Items</h2>
                    <table class="w-full text-sm">
                        <thead><tr class="text-[10px] font-black uppercase tracking-widest text-gray-500 border-b border-gray-800"><th class="pb-2 text-right">Product</th><th class="pb-2 text-right">Price</th><th class="pb-2 text-right">Qty</th><th class="pb-2 text-right">Total</th></tr></thead>
                        <tbody class="divide-y divide-gray-800">
                            @foreach ($order->items as $item)
                                <tr><td class="py-2 text-white">{{ $item->product_name }}</td><td class="py-2 text-gray-400">{{ number_format((float) $item->price, 2) }}</td><td class="py-2 text-gray-400">{{ $item->quantity }}</td><td class="py-2 font-black text-lime-400">{{ number_format((float) $item->subtotal, 2) }}</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="bg-[#171717] border border-gray-800 rounded-2xl p-5">
                    <h2 class="font-black text-white mb-3">Shipping</h2>
                    <dl class="grid grid-cols-2 gap-3 text-sm">
                        <div><dt class="text-xs text-gray-500">Recipient</dt><dd class="text-white font-bold">{{ $order->shipping_name }}</dd></div>
                        <div><dt class="text-xs text-gray-500">Phone</dt><dd class="text-white font-bold" dir="ltr">{{ $order->shipping_phone }}</dd></div>
                        <div><dt class="text-xs text-gray-500">City</dt><dd class="text-white font-bold">{{ $order->shipping_city }}</dd></div>
                        <div><dt class="text-xs text-gray-500">Address</dt><dd class="text-white font-bold">{{ $order->shipping_address }}</dd></div>
                    </dl>
                </div>
            </div>

            <div class="space-y-4">
                <div class="bg-[#171717] border border-gray-800 rounded-2xl p-5 space-y-2 text-sm">
                    <h2 class="font-black text-white mb-2">Summary</h2>
                    <div class="flex justify-between"><span class="text-gray-500">Subtotal</span><span class="text-white font-bold">{{ number_format((float) $order->subtotal, 2) }} LYD</span></div>
                    @if ((float) $order->discount > 0)<div class="flex justify-between"><span class="text-lime-400">Discount</span><span class="text-lime-400 font-bold">-{{ number_format((float) $order->discount, 2) }} LYD</span></div>@endif
                    <div class="flex justify-between"><span class="text-gray-500">Shipping</span><span class="text-emerald-400 font-bold">{{ (float) $order->shipping_cost > 0 ? number_format((float) $order->shipping_cost, 2) . ' LYD' : 'Free' }}</span></div>
                    <div class="flex justify-between pt-2 border-t border-gray-800"><span class="font-black text-white">Total</span><span class="font-black text-lime-400 text-lg">{{ number_format((float) $order->total, 2) }} LYD</span></div>
                    @if ((float) $order->commission_amount > 0)
                        <div class="pt-2 mt-2 border-t border-gray-800 space-y-1 text-xs">
                            <div class="flex justify-between"><span class="text-gray-500">Platform commission ({{ (float) $order->commission_rate }}%)</span><span class="text-lime-400">{{ number_format((float) $order->commission_amount, 2) }} LYD</span></div>
                            <div class="flex justify-between"><span class="text-gray-500">Vendor net</span><span class="text-white">{{ number_format((float) $order->vendor_net, 2) }} LYD</span></div>
                        </div>
                    @endif
                </div>
                <div class="bg-[#171717] border border-gray-800 rounded-2xl p-5">
                    <h2 class="font-black text-white mb-2">Timeline</h2>
                    <ul class="space-y-1.5 text-xs">
                        <li class="flex justify-between"><span class="text-gray-500">Created</span><span class="text-white">{{ $order->created_at->format('m-d H:i') }}</span></li>
                        @if ($order->confirmed_at)<li class="flex justify-between"><span class="text-gray-500">Confirmed</span><span class="text-white">{{ $order->confirmed_at->format('m-d H:i') }}</span></li>@endif
                        @if ($order->shipped_at)<li class="flex justify-between"><span class="text-gray-500">Shipped</span><span class="text-white">{{ $order->shipped_at->format('m-d H:i') }}</span></li>@endif
                        @if ($order->delivered_at)<li class="flex justify-between"><span class="text-gray-500">Delivered</span><span class="text-white">{{ $order->delivered_at->format('m-d H:i') }}</span></li>@endif
                        @if ($order->cancelled_at)<li class="flex justify-between text-red-400"><span>Cancelled</span><span>{{ $order->cancelled_at->format('m-d H:i') }}</span></li>@endif
                    </ul>
                </div>
                <div class="bg-[#171717] border border-gray-800 rounded-2xl p-5 text-sm">
                    <h2 class="font-black text-white mb-2">Customer</h2>
                    <div class="font-bold text-white">{{ $order->user?->name ?? '—' }}</div>
                    <div class="text-xs text-gray-500">{{ $order->user?->email }}</div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>