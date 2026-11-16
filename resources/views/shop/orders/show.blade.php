<x-layouts.shop :title="$order->order_number">
    <nav class="text-xs font-semibold text-gray-500 mb-6 flex items-center flex-wrap gap-1">
        <a href="{{ route('shop.orders.index') }}" class="hover:text-lime-400">My Orders</a>
        <span class="text-gray-700">/</span>
        <span class="text-white">{{ $order->order_number }}</span>
    </nav>

    <div class="flex items-start justify-between gap-3 flex-wrap mb-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-white font-mono">{{ $order->order_number }}</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $order->created_at->format('M d, Y · H:i') }} &middot; {{ $order->vendor->name }}</p>
        </div>
        <span class="px-3 py-1.5 text-xs font-black uppercase tracking-widest rounded-md {{ $order->status->badgeClass() }}">{{ $order->status->label() }}</span>
    </div>

    @if ($order->status->value === 'pending_customer_approval' && $order->hasPendingModification())
        <div class="mb-5 rounded-2xl border-2 border-purple-500/40 bg-purple-500/10 p-5">
            <div class="flex items-start gap-4 flex-wrap">
                <div class="w-10 h-10 rounded-full bg-purple-500/20 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5 19h14a2 2 0 001.84-2.75L13.74 4a2 2 0 00-3.5 0L3.34 16.25A2 2 0 005 19z"/></svg>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <h2 class="font-black text-purple-300 text-lg">Action needed: vendor proposed changes</h2>
                    <p class="text-sm text-purple-400 mt-1">{{ $order->vendor->name }} suggested modifications. Please review and accept or reject.</p>
                </div>
                <a href="{{ route('shop.orders.review', $order) }}" class="shrink-0 px-4 py-2 bg-purple-500 hover:bg-purple-400 text-white font-black rounded-lg text-sm">Review &rarr;</a>
            </div>
        </div>
    @endif

    <x-order-timeline :order="$order" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mt-6">
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-[#171717] border border-gray-800 rounded-2xl p-5">
                <h2 class="font-black text-white mb-3">Items</h2>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-[10px] font-black uppercase tracking-widest text-gray-500 border-b border-gray-800">
                            <th class="pb-2 text-right">Product</th>
                            <th class="pb-2 text-right">Price</th>
                            <th class="pb-2 text-right">Qty</th>
                            <th class="pb-2 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @foreach ($order->items as $item)
                            <tr>
                                <td class="py-2.5 text-white">{{ $item->product_name }}</td>
                                <td class="py-2.5 text-gray-400">{{ number_format((float) $item->price, 2) }} LYD</td>
                                <td class="py-2.5 text-gray-400">{{ $item->quantity }}</td>
                                <td class="py-2.5 font-black text-lime-400">{{ number_format((float) $item->subtotal, 2) }} LYD</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="bg-[#171717] border border-gray-800 rounded-2xl p-5">
                <h2 class="font-black text-white mb-3">Shipping details</h2>
                <dl class="grid grid-cols-2 gap-3 text-sm">
                    <div><dt class="text-[10px] font-black uppercase tracking-widest text-gray-500">Recipient</dt><dd class="text-white font-bold mt-0.5">{{ $order->shipping_name }}</dd></div>
                    <div><dt class="text-[10px] font-black uppercase tracking-widest text-gray-500">Phone</dt><dd class="text-white font-bold mt-0.5" dir="ltr">{{ $order->shipping_phone }}</dd></div>
                    <div><dt class="text-[10px] font-black uppercase tracking-widest text-gray-500">City</dt><dd class="text-white font-bold mt-0.5">{{ $order->shipping_city }}</dd></div>
                    <div><dt class="text-[10px] font-black uppercase tracking-widest text-gray-500">Address</dt><dd class="text-white font-bold mt-0.5">{{ $order->shipping_address }}</dd></div>
                    @if ($order->notes)
                        <div class="col-span-2"><dt class="text-[10px] font-black uppercase tracking-widest text-gray-500">Notes</dt><dd class="text-gray-300 mt-0.5">{{ $order->notes }}</dd></div>
                    @endif
                </dl>
            </div>
        </div>

        <div class="space-y-4">
            <div class="bg-[#171717] border border-gray-800 rounded-2xl p-5 space-y-2 text-sm">
                <h2 class="font-black text-white mb-2">Summary</h2>
                <div class="flex justify-between"><span class="text-gray-500">Subtotal</span><span class="font-bold text-white">{{ number_format((float) $order->subtotal, 2) }} LYD</span></div>
                @if ((float) $order->discount > 0)
                    <div class="flex justify-between"><span class="text-lime-400">Discount</span><span class="font-bold text-lime-400">-{{ number_format((float) $order->discount, 2) }} LYD</span></div>
                @endif
                <div class="flex justify-between"><span class="text-gray-500">Shipping</span><span class="font-bold text-emerald-400">{{ (float) $order->shipping_cost > 0 ? number_format((float) $order->shipping_cost, 2) . ' LYD' : 'Free' }}</span></div>
                <div class="flex justify-between pt-2 border-t border-gray-800"><span class="font-black text-white">Total</span><span class="font-black text-lime-400 text-lg">{{ number_format((float) $order->total, 2) }} LYD</span></div>
                <div class="pt-2 border-t border-gray-800 text-xs">
                    <div class="text-gray-500 mb-1">Payment</div>
                    <div class="text-white font-bold">{{ $order->payment_method === 'cash_on_delivery' ? 'Cash on Delivery' : $order->payment_method }}</div>
                </div>
                @if (in_array($order->status->value, ['confirmed', 'shipped', 'delivered', 'cancelled']))
                    <a href="{{ route('shop.invoices.show', $order) }}" class="block mt-3 w-full text-center px-3 py-2.5 bg-indigo-500 hover:bg-indigo-400 text-white font-black rounded-lg text-xs">View invoice</a>
                @else
                    <p class="text-[10px] text-gray-500 text-center pt-2">Invoice available after vendor confirms your order.</p>
                @endif
            </div>

            <div class="bg-[#171717] border border-gray-800 rounded-2xl p-5">
                <h2 class="font-black text-white mb-2">Vendor</h2>
                <a href="{{ route('shop.stores.show', ['vendor' => $order->vendor->slug]) }}" class="text-lime-400 hover:text-lime-300 font-bold text-sm">{{ $order->vendor->name }} &rarr;</a>
                @if ($order->vendor->phone)
                    <div class="text-xs text-gray-500 mt-1" dir="ltr">{{ $order->vendor->phone }}</div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.shop>