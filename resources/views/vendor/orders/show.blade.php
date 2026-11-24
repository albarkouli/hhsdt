<x-layouts.dashboard :title="$order->order_number">
    <div class="space-y-6">
        <div class="flex items-start justify-between flex-wrap gap-3">
            <div>
                <a href="{{ route('vendor.orders.index') }}" class="text-xs font-bold text-[#A8B5C1] hover:text-gold">&larr; All orders</a>
                <h1 class="text-3xl font-black text-[#D6DEE6] mt-1 font-mono">{{ $order->order_number }}</h1>
                <p class="text-sm text-[#A8B5C1] mt-1">{{ $order->created_at->format('Y-m-d H:i') }}</p>
            </div>
            <span class="px-3 py-1.5 text-xs font-black uppercase tracking-widest rounded-md {{ $order->status->badgeClass() }}">{{ $order->status->label() }}</span>
        </div>

        @if (session('status'))
            <div class="rounded-xl border border-gold/30 bg-gold/10 p-3 text-sm text-gold-hover">{{ session('status') }}</div>
        @endif
        @if (session('error'))
            <div class="rounded-xl border border-red-500/30 bg-red-500/10 p-3 text-sm text-red-300">{{ session('error') }}</div>
        @endif

        {{-- Actions --}}
        @php
            $hasPendingMod = $order->hasPendingModification();
            $canModify = in_array($order->status->value, ['pending', 'confirmed'], true) && ! $hasPendingMod;
        @endphp
        @if ($hasPendingMod)
            <div class="bg-purple-500/10 border border-purple-500/30 rounded-xl p-4">
                <div class="font-black text-purple-300">Awaiting customer approval</div>
                <p class="text-sm text-purple-400 mt-1">You proposed changes and the customer hasn't responded yet.</p>
            </div>
        @else
            <div class="bg-[#171717] border border-[#344453] rounded-xl p-4 flex flex-wrap gap-2">
                @if ($order->status->value === 'pending')
                    <form method="POST" action="{{ route('vendor.orders.confirm', $order) }}">
                        @csrf
                        <button class="px-4 py-2 bg-gold hover:bg-gold-hover text-[#D6DEE6] font-black rounded-lg text-sm">Confirm order</button>
                    </form>
                @elseif ($order->status->value === 'confirmed')
                    <form method="POST" action="{{ route('vendor.orders.ship', $order) }}">
                        @csrf
                        <button class="px-4 py-2 bg-indigo-500 hover:bg-indigo-400 text-[#D6DEE6] font-black rounded-lg text-sm">Ship order</button>
                    </form>
                @elseif ($order->status->value === 'shipped')
                    <form method="POST" action="{{ route('vendor.orders.deliver', $order) }}">
                        @csrf
                        <button class="px-4 py-2 bg-emerald-500 hover:bg-emerald-400 text-[#D6DEE6] font-black rounded-lg text-sm">Mark as delivered</button>
                    </form>
                @endif

                @if ($canModify)
                    <a href="{{ route('vendor.orders.modify.form', $order) }}" class="px-4 py-2 bg-amber-500 hover:bg-amber-400 text-[#D6DEE6] font-black rounded-lg text-sm">Propose changes</a>
                @endif

                @if ($order->isCancellable())
                    <form method="POST" action="{{ route('vendor.orders.cancel', $order) }}" onsubmit="return confirm('Cancel this order? Stock will be restored.')">
                        @csrf
                        <button class="px-4 py-2 bg-red-500/20 border border-red-500/30 text-red-400 hover:bg-red-500/30 font-black rounded-lg text-sm">Cancel</button>
                    </form>
                @endif

                @if ($order->status->isFinal())
                    <span class="text-sm text-[#A8B5C1] self-center">This order is final.</span>
                @endif
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div class="lg:col-span-2 space-y-4">
                {{-- Items --}}
                <div class="bg-[#171717] border border-[#344453] rounded-2xl p-5">
                    <h2 class="font-black text-[#D6DEE6] mb-3">Items</h2>
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-[10px] font-black uppercase tracking-widest text-[#A8B5C1] border-b border-[#344453]">
                                <th class="pb-2 text-right">Product</th>
                                <th class="pb-2 text-right">Price</th>
                                <th class="pb-2 text-right">Qty</th>
                                <th class="pb-2 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800">
                            @foreach ($order->items as $item)
                                <tr>
                                    <td class="py-2 text-[#D6DEE6]">{{ $item->product_name }}</td>
                                    <td class="py-2 text-[#A8B5C1]">{{ number_format((float) $item->price, 2) }} LYD</td>
                                    <td class="py-2 text-[#A8B5C1]">{{ $item->quantity }}</td>
                                    <td class="py-2 font-black text-gold">{{ number_format((float) $item->subtotal, 2) }} LYD</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Shipping --}}
                <div class="bg-[#171717] border border-[#344453] rounded-2xl p-5">
                    <h2 class="font-black text-[#D6DEE6] mb-3">Shipping to</h2>
                    <dl class="grid grid-cols-2 gap-3 text-sm">
                        <div><dt class="text-xs text-[#A8B5C1]">Recipient</dt><dd class="text-[#D6DEE6] font-bold">{{ $order->shipping_name }}</dd></div>
                        <div><dt class="text-xs text-[#A8B5C1]">Phone</dt><dd class="text-[#D6DEE6] font-bold" dir="ltr">{{ $order->shipping_phone }}</dd></div>
                        <div><dt class="text-xs text-[#A8B5C1]">City</dt><dd class="text-[#D6DEE6] font-bold">{{ $order->shipping_city }}</dd></div>
                        <div><dt class="text-xs text-[#A8B5C1]">Address</dt><dd class="text-[#D6DEE6] font-bold">{{ $order->shipping_address }}</dd></div>
                        @if ($order->notes)
                            <div class="col-span-2"><dt class="text-xs text-[#A8B5C1]">Customer notes</dt><dd class="text-[#D6DEE6]">{{ $order->notes }}</dd></div>
                        @endif
                    </dl>
                </div>
            </div>

            <div class="space-y-4">
                {{-- Summary --}}
                <div class="bg-[#171717] border border-[#344453] rounded-2xl p-5 space-y-2 text-sm">
                    <h2 class="font-black text-[#D6DEE6] mb-2">Summary</h2>
                    <div class="flex justify-between"><span class="text-[#A8B5C1]">Subtotal</span><span class="font-bold text-[#D6DEE6]">{{ number_format((float) $order->subtotal, 2) }} LYD</span></div>
                    @if ((float) $order->discount > 0)
                        <div class="flex justify-between"><span class="text-gold">Discount</span><span class="font-bold text-gold">-{{ number_format((float) $order->discount, 2) }} LYD</span></div>
                    @endif
                    <div class="flex justify-between"><span class="text-[#A8B5C1]">Shipping</span><span class="font-bold text-emerald-400">{{ (float) $order->shipping_cost > 0 ? number_format((float) $order->shipping_cost, 2) . ' LYD' : 'Free' }}</span></div>
                    <div class="flex justify-between pt-2 border-t border-[#344453]"><span class="font-black text-[#D6DEE6]">Total</span><span class="font-black text-gold text-lg">{{ number_format((float) $order->total, 2) }} LYD</span></div>

                    @if ((float) $order->commission_amount > 0)
                        <div class="pt-3 mt-2 border-t border-[#344453] space-y-1.5">
                            <div class="flex justify-between text-xs"><span class="text-[#A8B5C1]">Your earnings</span><span class="text-[#D6DEE6]">{{ number_format((float) $order->subtotal - (float) $order->discount, 2) }} LYD</span></div>
                            <div class="flex justify-between text-xs"><span class="text-red-400">Platform ({{ (float) $order->commission_rate }}%)</span><span class="text-red-400">-{{ number_format((float) $order->commission_amount, 2) }} LYD</span></div>
                            <div class="flex justify-between font-black text-gold pt-1.5 border-t border-[#344453]"><span>You receive</span><span>{{ number_format((float) $order->vendor_net, 2) }} LYD</span></div>
                        </div>
                    @endif
                </div>

                {{-- Timeline --}}
                <div class="bg-[#171717] border border-[#344453] rounded-2xl p-5">
                    <h2 class="font-black text-[#D6DEE6] mb-3">Timeline</h2>
                    <ul class="space-y-2 text-sm">
                        <li class="flex justify-between"><span class="text-[#A8B5C1]">Created</span><span class="text-[#D6DEE6]">{{ $order->created_at->format('Y-m-d H:i') }}</span></li>
                        @if ($order->confirmed_at)<li class="flex justify-between"><span class="text-[#A8B5C1]">Confirmed</span><span class="text-[#D6DEE6]">{{ $order->confirmed_at->format('Y-m-d H:i') }}</span></li>@endif
                        @if ($order->shipped_at)<li class="flex justify-between"><span class="text-[#A8B5C1]">Shipped</span><span class="text-[#D6DEE6]">{{ $order->shipped_at->format('Y-m-d H:i') }}</span></li>@endif
                        @if ($order->delivered_at)<li class="flex justify-between"><span class="text-[#A8B5C1]">Delivered</span><span class="text-[#D6DEE6]">{{ $order->delivered_at->format('Y-m-d H:i') }}</span></li>@endif
                        @if ($order->cancelled_at)<li class="flex justify-between text-red-400"><span>Cancelled</span><span>{{ $order->cancelled_at->format('Y-m-d H:i') }}</span></li>@endif
                    </ul>
                </div>

                {{-- Customer --}}
                <div class="bg-[#171717] border border-[#344453] rounded-2xl p-5">
                    <h2 class="font-black text-[#D6DEE6] mb-2">Customer</h2>
                    <div class="text-sm font-bold text-[#D6DEE6]">{{ $order->user?->name ?? 'أ¢â‚¬â€‌' }}</div>
                    <div class="text-xs text-[#A8B5C1]">{{ $order->user?->email }}</div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>