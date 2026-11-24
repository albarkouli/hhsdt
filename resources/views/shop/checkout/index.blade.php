<x-layouts.shop :title="'Checkout'">
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <a href="{{ route('shop.cart.index') }}" class="text-xs font-bold text-[#A8B5C1] hover:text-gold">&larr; Back to cart</a>
            <h1 class="text-3xl sm:text-4xl font-black text-[#D6DEE6] mt-2">Checkout</h1>
        </div>

        @if ($groups->count() > 1)
            <div class="mb-5 rounded-xl border border-gold/30 bg-gold/10 p-4 text-sm text-gold-hover flex items-start gap-2">
                <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Your cart has products from <strong>{{ $groups->count() }} vendors</strong>. You'll receive {{ $groups->count() }} separate orders.</span>
            </div>
        @endif

        <form method="POST" action="{{ route('shop.checkout.store') }}">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- LEFT: FORM --}}
                <div class="lg:col-span-2 space-y-5">
                    <div class="bg-[#171717] border border-[#344453] rounded-2xl p-6">
                        <h2 class="font-black text-[#D6DEE6] text-lg mb-4 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-gold text-[#D6DEE6] flex items-center justify-center text-xs font-black">1</span>
                            Shipping details
                        </h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-black uppercase tracking-widest text-[#A8B5C1] mb-1.5">Full name *</label>
                                <input id="shipping_name" name="shipping_name" type="text" required maxlength="100"
                                       value="{{ old('shipping_name', $user->name) }}"
                                       class="w-full rounded-lg bg-[#263543] border border-[#344453] text-[#D6DEE6] text-sm px-3 py-2.5 focus:border-gold focus:ring-2 focus:ring-gold/20">
                                @error('shipping_name')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-xs font-black uppercase tracking-widest text-[#A8B5C1] mb-1.5">Phone *</label>
                                <input id="shipping_phone" name="shipping_phone" type="text" required maxlength="30"
                                       value="{{ old('shipping_phone') }}" placeholder="09XXXXXXXX"
                                       class="w-full rounded-lg bg-[#263543] border border-[#344453] text-[#D6DEE6] text-sm px-3 py-2.5 focus:border-gold focus:ring-2 focus:ring-gold/20">
                                @error('shipping_phone')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="block text-xs font-black uppercase tracking-widest text-[#A8B5C1] mb-1.5">City *</label>
                            <input id="shipping_city" name="shipping_city" type="text" required maxlength="100"
                                   value="{{ old('shipping_city') }}"
                                   class="w-full rounded-lg bg-[#263543] border border-[#344453] text-[#D6DEE6] text-sm px-3 py-2.5 focus:border-gold focus:ring-2 focus:ring-gold/20">
                            @error('shipping_city')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                        </div>

                        <div class="mt-4">
                            <label class="block text-xs font-black uppercase tracking-widest text-[#A8B5C1] mb-1.5">Address *</label>
                            <input id="shipping_address" name="shipping_address" type="text" required maxlength="255"
                                   value="{{ old('shipping_address') }}" placeholder="Street, building, landmark"
                                   class="w-full rounded-lg bg-[#263543] border border-[#344453] text-[#D6DEE6] text-sm px-3 py-2.5 focus:border-gold focus:ring-2 focus:ring-gold/20">
                            @error('shipping_address')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                        </div>

                        <div class="mt-4">
                            <label class="block text-xs font-black uppercase tracking-widest text-[#A8B5C1] mb-1.5">Order notes (optional)</label>
                            <textarea id="notes" name="notes" rows="2" maxlength="1000"
                                      class="w-full rounded-lg bg-[#263543] border border-[#344453] text-[#D6DEE6] text-sm px-3 py-2.5 focus:border-gold focus:ring-2 focus:ring-gold/20">{{ old('notes') }}</textarea>
                            @error('notes')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div class="bg-[#171717] border border-[#344453] rounded-2xl p-6">
                        <h2 class="font-black text-[#D6DEE6] text-lg mb-4 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-gold text-[#D6DEE6] flex items-center justify-center text-xs font-black">2</span>
                            Payment method
                        </h2>

                        <label class="flex items-start gap-3 p-4 border-2 border-gold bg-gold/5 rounded-xl cursor-pointer">
                            <input type="radio" name="payment_method" value="cash_on_delivery" checked class="mt-1 w-4 h-4 text-gold bg-[#263543] border-[#344453] focus:ring-gold">
                            <div>
                                <div class="font-black text-[#D6DEE6] flex items-center gap-2">
                                    Cash on Delivery
                                    <svg class="w-4 h-4 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div class="text-xs text-[#A8B5C1] mt-1">Pay in cash when your order arrives</div>
                            </div>
                        </label>

                        <p class="mt-3 text-[10px] text-[#A8B5C1] flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            More payment methods (cards, bank transfer) coming soon
                        </p>
                    </div>

                    <div class="bg-[#171717] border border-[#344453] rounded-2xl p-6">
                        <h2 class="font-black text-[#D6DEE6] text-lg mb-4 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-gold text-[#D6DEE6] flex items-center justify-center text-xs font-black">3</span>
                            Your items
                        </h2>

                        @foreach ($groups as $vendorId => $groupItems)
                            <div class="mb-4 last:mb-0">
                                <div class="text-[10px] font-black uppercase tracking-widest text-[#A8B5C1] mb-2 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/></svg>
                                    {{ $groupItems->first()->product->vendor->name }}
                                </div>
                                @foreach ($groupItems as $item)
                                    <div class="flex justify-between text-sm py-1.5 border-b border-[#344453] last:border-0">
                                        <span class="text-[#D6DEE6]">{{ $item->product->name }} <span class="text-[#A8B5C1]">ط£â€” {{ $item->quantity }}</span></span>
                                        <span class="font-bold text-[#D6DEE6]">{{ number_format($item->subtotal, 2) }} LYD</span>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
                {{-- RIGHT: SUMMARY --}}
                <div class="lg:col-span-1">
                    <div class="bg-[#171717] border border-[#344453] rounded-2xl p-5 space-y-3 lg:sticky lg:top-32">
                        <h2 class="font-black text-[#D6DEE6] text-lg">Summary</h2>

                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-[#A8B5C1]">Subtotal</span>
                                <span class="font-bold text-[#D6DEE6]">{{ number_format($subtotal, 2) }} LYD</span>
                            </div>
                            @if ($coupon && $discount > 0)
                                <div class="flex justify-between">
                                    <span class="text-gold">Discount ({{ $coupon->code }})</span>
                                    <span class="font-bold text-gold">-{{ number_format($discount, 2) }} LYD</span>
                                </div>
                            @endif
                            <div class="flex justify-between">
                                <span class="text-[#A8B5C1]">Shipping</span>
                                <span class="font-bold text-emerald-400">Free</span>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-[#344453] flex justify-between items-baseline">
                            <span class="font-black text-[#D6DEE6]">Total</span>
                            <span class="font-black text-gold text-2xl">{{ number_format($grandTotal, 2) }} <span class="text-xs text-[#A8B5C1]">LYD</span></span>
                        </div>

                        <button type="submit" class="w-full bg-gold hover:bg-gold-hover text-[#D6DEE6] font-black py-4 rounded-lg shadow-[0_0_30px_rgba(163,230,53,0.25)] text-sm">
                            Place order
                        </button>

                        <p class="text-[10px] text-center text-[#A8B5C1]">
                            By placing this order you agree to our terms of service.
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-layouts.shop>