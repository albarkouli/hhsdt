<x-layouts.shop :title="'Cart'">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl sm:text-4xl font-black text-[#D6DEE6]">Your cart</h1>
        @unless ($items->isEmpty())
            <form method="POST" action="{{ route('shop.cart.clear') }}">
                @csrf @method('DELETE')
                <button type="submit" class="text-sm font-bold text-[#A8B5C1] hover:text-red-400" onclick="return confirm('Clear cart?')">Clear cart</button>
            </form>
        @endunless
    </div>

    @if ($items->isEmpty())
        <div class="bg-[#171717] border border-[#344453] rounded-2xl p-16 text-center">
            <div class="w-20 h-20 rounded-full bg-[#263543] flex items-center justify-center mx-auto mb-5">
                <svg class="w-10 h-10 text-[#A8B5C1]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <h3 class="text-xl font-black text-[#D6DEE6] mb-2">Your cart is empty</h3>
            <p class="text-sm text-[#A8B5C1] mb-6">Start shopping to add items</p>
            <a href="{{ route('shop.products.index') }}" class="inline-block px-8 py-3.5 bg-gold text-[#D6DEE6] font-black rounded-xl hover:bg-gold-hover">Browse products</a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-3">
                @foreach ($items as $item)
                    @php $p = $item->product; @endphp
                    <div class="bg-[#171717] border {{ $item->is_visible ? 'border-[#344453]' : 'border-red-500/30 bg-red-500/5' }} rounded-xl p-4 flex gap-4">
                        <div class="w-24 h-24 rounded-lg bg-[#263543] overflow-hidden shrink-0">
                            @if ($p->image)
                                <img src="{{ Storage::url($p->image) }}" alt="{{ $p->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-3xl text-[#A8B5C1] font-black">{{ Str::substr($p->name, 0, 1) }}</div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-3 mb-1">
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-bold text-[#D6DEE6] text-sm line-clamp-2">{{ $p->name }}</h3>
                                    <p class="text-xs text-[#A8B5C1] mt-0.5">{{ $p->vendor?->name }}</p>
                                    @unless ($item->is_visible)
                                        <span class="inline-block mt-1 text-[10px] font-black uppercase tracking-wider text-red-400 bg-red-500/10 border border-red-500/30 px-1.5 py-0.5 rounded">Unavailable</span>
                                    @endunless
                                </div>
                                <form method="POST" action="{{ route('shop.cart.remove', $p) }}">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-[#A8B5C1] hover:text-red-400 p-1" title="Remove">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </form>
                            </div>
                            <div class="flex items-end justify-between mt-3">
                                @if ($item->is_visible)
                                    <form method="POST" action="{{ route('shop.cart.update', $p) }}" class="flex items-center gap-2">
                                        @csrf @method('PATCH')
                                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $p->stock }}"
                                               class="w-16 rounded-md bg-[#263543] border border-[#344453] text-[#D6DEE6] text-sm font-bold text-center py-1.5 focus:border-gold">
                                        <button type="submit" class="text-xs font-bold text-gold hover:text-gold-hover">Update</button>
                                    </form>
                                @else
                                    <span class="text-xs text-[#A8B5C1]">Qty: {{ $item->quantity }}</span>
                                @endif
                                <div class="text-right">
                                    <div class="font-black text-gold text-base">{{ number_format($item->is_visible ? $item->subtotal : 0, 2) }} <span class="text-xs text-[#A8B5C1]">LYD</span></div>
                                    @if ($item->is_visible && $item->quantity > 1)
                                        <div class="text-[10px] text-[#A8B5C1]">{{ number_format((float) $p->price, 2) }} each</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="lg:col-span-1">
                <div class="bg-[#171717] border border-[#344453] rounded-2xl p-5 space-y-4 lg:sticky lg:top-32">
                    <h2 class="font-black text-[#D6DEE6] text-lg">Order summary</h2>

                    {{-- Coupon --}}
                    @auth
                        @php $appliedCoupon = app(\App\Services\CartService::class)->getCoupon(); @endphp
                        @if ($appliedCoupon)
                            <div class="bg-gold/10 border border-gold/30 rounded-lg p-3">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="font-mono font-black text-gold text-sm">{{ $appliedCoupon->code }}</div>
                                        <div class="text-[10px] text-[#A8B5C1] mt-0.5">{{ $appliedCoupon->name }}</div>
                                    </div>
                                    <form method="POST" action="{{ route('shop.cart.coupon.remove') }}">
                                        @csrf @method('DELETE')
                                        <button class="text-xs font-bold text-red-400 hover:text-red-300">Remove</button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <form method="POST" action="{{ route('shop.cart.coupon.apply') }}" class="flex gap-2">
                                @csrf
                                <input name="code" type="text" placeholder="Coupon code" maxlength="30"
                                       class="flex-1 rounded-lg bg-[#263543] border border-[#344453] text-[#D6DEE6] text-sm font-mono uppercase px-3 py-2 placeholder-gray-600 focus:border-gold" style="text-transform:uppercase">
                                <button type="submit" class="px-4 py-2 bg-[#2D3D4C] text-[#D6DEE6] text-xs font-black rounded-lg hover:bg-[#2D3D4C]">Apply</button>
                            </form>
                        @endif
                    @else
                        <div class="rounded-lg bg-[#263543] border border-[#344453] p-3 text-xs text-[#A8B5C1] text-center">
                            <a href="{{ route('login') }}" class="text-gold font-bold hover:text-gold-hover">Login</a> to use coupons
                        </div>
                    @endauth

                    {{-- Totals --}}
                    <div class="space-y-2 pt-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-[#A8B5C1]">Subtotal</span>
                            <span class="font-bold text-[#D6DEE6]">{{ number_format($subtotal, 2) }} LYD</span>
                        </div>
                        @if ($appliedCoupon)
                            <div class="flex justify-between text-sm">
                                <span class="text-gold">Discount ({{ $appliedCoupon->code }})</span>
                                <span class="font-bold text-gold">-{{ number_format(app(\App\Services\CartService::class)->discount(), 2) }} LYD</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-sm">
                            <span class="text-[#A8B5C1]">Shipping</span>
                            <span class="font-bold text-emerald-400">Free</span>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-[#344453] flex justify-between items-baseline">
                        <span class="font-black text-[#D6DEE6]">Total</span>
                        <span class="font-black text-gold text-2xl">{{ number_format(app(\App\Services\CartService::class)->total(), 2) }} <span class="text-sm text-[#A8B5C1]">LYD</span></span>
                    </div>

                    @auth
                        <a href="{{ route('shop.checkout.index') }}" class="block text-center w-full bg-gold hover:bg-gold-hover text-[#D6DEE6] font-black py-3.5 rounded-lg shadow-[0_0_30px_rgba(163,230,53,0.25)]">
                            Proceed to checkout
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="block text-center w-full bg-gold hover:bg-gold-hover text-[#D6DEE6] font-black py-3.5 rounded-lg shadow-[0_0_30px_rgba(163,230,53,0.25)]">
                            Login to checkout
                        </a>
                        <p class="text-[10px] text-center text-[#A8B5C1]">You need an account to place an order</p>
                    @endauth
                </div>
            </div>
        </div>
    @endif
</x-layouts.shop>