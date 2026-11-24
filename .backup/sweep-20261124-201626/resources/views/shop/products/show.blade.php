<x-layouts.shop :title="$product->name">
    @php
        $isNew = $product->created_at->gt(now()->subDays(7));
        $inStock = $product->stock > 0;
        $waPhone = $product->vendor->phone ? preg_replace('/[^0-9]/', '', $product->vendor->phone) : null;
        $waMessage = urlencode("Hello {$product->vendor->name}, I'm interested in \"{$product->name}\" (from WESALKOM).");
    @endphp

    <nav class="text-xs font-semibold text-gray-500 mb-6 flex items-center flex-wrap gap-1">
        <a href="{{ route('home') }}" class="hover:text-lime-400">Home</a>
        <span class="text-gray-700">/</span>
        <a href="{{ route('shop.products.index') }}" class="hover:text-lime-400">Shop</a>
        @if ($product->category)
            <span class="text-gray-700">/</span>
            <a href="{{ route('shop.products.index', ['category' => $product->category->slug]) }}" class="hover:text-lime-400">{{ $product->category->name }}</a>
        @endif
        <span class="text-gray-700">/</span>
        <span class="text-white">{{ Str::limit($product->name, 40) }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 mb-16">
        {{-- IMAGE --}}
        <div class="space-y-3">
            <div class="aspect-square rounded-2xl overflow-hidden bg-[#171717] border border-gray-800">
                @if ($product->image)
                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full bg-gradient-to-br from-gray-800 to-gray-900 flex flex-col items-center justify-center p-8 text-center">
                        <div class="text-8xl mb-4 opacity-50">
                            <svg class="w-24 h-24 mx-auto text-lime-400/20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                        <div class="text-2xl font-black text-white mb-1">{{ $product->name }}</div>
                    </div>
                @endif
            </div>

            <div class="flex flex-wrap gap-2">
                @if ($isNew)<span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-md bg-lime-400/10 text-lime-400 border border-lime-400/30">New arrival</span>@endif
                @if ($inStock && $product->stock < 5)<span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-md bg-orange-500/10 text-orange-400 border border-orange-500/30">Only {{ $product->stock }} left</span>@endif
                @if (! $inStock)<span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-md bg-red-500/10 text-red-400 border border-red-500/30">Out of stock</span>@endif
            </div>
        </div>

        {{-- DETAILS --}}
        <div class="space-y-5">
            <div>
                @if ($product->category)
                    <a href="{{ route('shop.products.index', ['category' => $product->category->slug]) }}" class="inline-block text-xs font-black uppercase tracking-widest text-lime-400 hover:text-lime-300 mb-2">{{ $product->category->name }}</a>
                @endif
                <h1 class="text-3xl sm:text-4xl font-black text-white leading-tight">{{ $product->name }}</h1>

                <a href="{{ route('shop.reviews.index', $product) }}" class="inline-flex items-center gap-2 mt-3 group">
                    <div class="flex text-lime-400 text-lg">
                        @for ($i = 1; $i <= 5; $i++)@if ($i <= round($product->avg_rating))<svg class="w-5 h-5 text-lime-400 inline-block" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>@else<svg class="w-5 h-5 text-gray-600 inline-block" fill="none" stroke="currentColor" viewBox="0 0 20 20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>@endif@endfor
                    </div>
                    <span class="text-sm font-semibold text-gray-400 group-hover:text-lime-400">
                        @if ($product->reviews_count > 0)
                            {{ number_format($product->avg_rating, 1) }} ({{ $product->reviews_count }} review{{ $product->reviews_count == 1 ? '' : 's' }})
                        @else
                            Be the first to review
                        @endif
                    </span>
                </a>
            </div>

            <div class="flex items-baseline gap-3">
                <div class="text-5xl font-black text-lime-400">{{ number_format((float) $product->price, 2) }}</div>
                <div class="text-lg text-gray-500 font-bold">LYD</div>
            </div>

            <div>
                @if ($product->stock > 5)
                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-emerald-500/10 text-emerald-400 text-sm font-bold border border-emerald-500/20">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>In stock ({{ $product->stock }} available)
                    </span>
                @elseif ($product->stock > 0)
                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-orange-500/10 text-orange-400 text-sm font-bold border border-orange-500/20">
                        <span class="w-2 h-2 rounded-full bg-orange-400 animate-pulse"></span>Only {{ $product->stock }} left
                    </span>
                @else
                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-red-500/10 text-red-400 text-sm font-bold border border-red-500/20">
                        <span class="w-2 h-2 rounded-full bg-red-400"></span>Out of stock
                    </span>
                @endif
            </div>
            {{-- WISHLIST --}}
            @auth
                @php $inWishlist = auth()->user()->hasInWishlist($product->id); @endphp
                <form method="POST" action="{{ route('shop.wishlist.toggle', $product) }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-5 py-3 rounded-xl border-2 transition font-bold text-sm {{ $inWishlist ? 'border-lime-400 bg-lime-400/10 text-lime-400' : 'border-gray-700 text-gray-300 hover:border-lime-400 hover:text-lime-400' }}">
                        @if ($inWishlist)<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/></svg>@else<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>@endif
                        {{ $inWishlist ? 'In wishlist' : 'Add to wishlist' }}
                    </button>
                </form>
            @endauth

            {{-- ADD TO CART --}}
            @if ($inStock)
                <form method="POST" action="{{ route('shop.cart.add', $product) }}" class="space-y-3">
                    @csrf
                    <label class="block text-xs font-black uppercase tracking-widest text-gray-500">Quantity</label>
                    <div class="flex gap-3">
                        <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}"
                               class="w-24 rounded-lg bg-gray-900 border border-gray-800 text-white text-center font-black text-lg py-3 focus:border-lime-400 focus:ring-2 focus:ring-lime-400/20">
                        <button type="submit" class="flex-1 bg-lime-400 hover:bg-lime-300 text-black font-black py-3.5 rounded-lg flex items-center justify-center gap-2 shadow-[0_0_30px_rgba(163,230,53,0.25)]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            Add to cart
                        </button>
                    </div>
                </form>
            @else
                <div class="bg-[#171717] border border-gray-800 rounded-xl p-4 text-center text-gray-500 font-bold">
                    Currently unavailable
                </div>
            @endif

            {{-- ACTIONS --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @if ($waPhone)
                    <a href="https://wa.me/{{ $waPhone }}?text={{ $waMessage }}" target="_blank" rel="noopener"
                       class="flex items-center justify-center gap-2 px-4 py-3 bg-emerald-500 hover:bg-emerald-400 text-white font-bold rounded-lg text-sm">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        WhatsApp
                    </a>
                @endif
                <a href="{{ route('shop.stores.show', ['vendor' => $product->vendor->slug]) }}"
                   class="flex items-center justify-center gap-2 px-4 py-3 border-2 border-gray-700 hover:border-lime-400 hover:text-lime-400 text-gray-300 font-bold rounded-lg text-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Visit store
                </a>
            </div>

            {{-- VENDOR MINI CARD --}}
            <a href="{{ route('shop.stores.show', ['vendor' => $product->vendor->slug]) }}" class="block bg-[#171717] border border-gray-800 hover:border-lime-400/30 rounded-xl p-4">
                <div class="flex items-center gap-3">
                    @if ($product->vendor->logo)
                        <img src="{{ Storage::url($product->vendor->logo) }}" alt="{{ $product->vendor->name }}" class="w-14 h-14 rounded-xl object-cover">
                    @else
                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-lime-400 to-emerald-500 flex items-center justify-center text-black text-xl font-black">{{ Str::substr($product->vendor->name, 0, 1) }}</div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <div class="text-[10px] font-black uppercase tracking-widest text-gray-500">Sold by</div>
                        <div class="font-black text-white truncate">{{ $product->vendor->name }}</div>
                        @if ($product->vendor->city)<div class="text-xs text-gray-500">{{ $product->vendor->city }}</div>@endif
                    </div>
                    <div class="text-xs font-black text-lime-400">Visit &rarr;</div>
                </div>
            </a>

            {{-- DESCRIPTION --}}
            @if ($product->description)
                <div class="bg-[#171717] border border-gray-800 rounded-xl p-5">
                    <h2 class="text-xs font-black uppercase tracking-widest text-gray-500 mb-3">Description</h2>
                    <p class="text-sm text-gray-300 whitespace-pre-line leading-relaxed">{{ $product->description }}</p>
                </div>
            @endif

            {{-- TRUST --}}
            <div class="grid grid-cols-3 gap-2">
                <div class="bg-[#171717] border border-gray-800 rounded-lg p-3 text-center">
                    <div class="text-2xl mb-1"><svg class="w-7 h-7 mx-auto text-lime-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1"/></svg></div>
                    <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Local delivery</div>
                </div>
                <div class="bg-[#171717] border border-gray-800 rounded-lg p-3 text-center">
                    <div class="text-2xl mb-1"><svg class="w-7 h-7 mx-auto text-lime-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg></div>
                    <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Cash on delivery</div>
                </div>
                <div class="bg-[#171717] border border-gray-800 rounded-lg p-3 text-center">
                    <div class="text-2xl mb-1"><svg class="w-7 h-7 mx-auto text-lime-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                    <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Verified vendor</div>
                </div>
            </div>
        </div>
    </div>
    {{-- RELATED --}}
    @if ($related->isNotEmpty())
        <section class="mb-14">
            <div class="flex items-end justify-between mb-5">
                <div>
                    <span class="text-xs uppercase tracking-widest text-lime-400 font-black mb-1 block">Similar</span>
                    <h2 class="text-2xl sm:text-3xl font-black text-white">You might also like</h2>
                </div>
                <a href="{{ route('shop.products.index', ['category' => $product->category?->slug]) }}" class="text-sm font-bold text-gray-400 hover:text-lime-400">View all &rarr;</a>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                @foreach ($related as $rel)
                    <a href="{{ route('shop.products.show', $rel) }}" class="group bg-[#171717] border border-gray-800 rounded-xl overflow-hidden flex flex-col hover:border-gray-700 hover:-translate-y-1">
                        <div class="aspect-square bg-gray-900 overflow-hidden">
                            @if ($rel->image)
                                <img src="{{ Storage::url($rel->image) }}" alt="{{ $rel->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-gray-800 to-gray-900 flex items-center justify-center text-4xl text-gray-700 font-black">{{ Str::substr($rel->name, 0, 1) }}</div>
                            @endif
                        </div>
                        <div class="p-3 flex-1 flex flex-col">
                            <h3 class="text-sm font-bold text-white line-clamp-2">{{ $rel->name }}</h3>
                            <p class="text-xs text-gray-500 mt-1">{{ $rel->vendor->name }}</p>
                            <div class="mt-auto pt-2 font-black text-lime-400 text-sm">{{ number_format((float) $rel->price, 2) }} <span class="text-gray-500 text-xs">LYD</span></div>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- MORE FROM VENDOR --}}
    @if ($moreFromVendor->isNotEmpty())
        <section>
            <div class="flex items-end justify-between mb-5">
                <div>
                    <span class="text-xs uppercase tracking-widest text-lime-400 font-black mb-1 block">From {{ $product->vendor->name }}</span>
                    <h2 class="text-2xl sm:text-3xl font-black text-white">More from this store</h2>
                </div>
                <a href="{{ route('shop.stores.show', ['vendor' => $product->vendor->slug]) }}" class="text-sm font-bold text-gray-400 hover:text-lime-400">Visit store &rarr;</a>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                @foreach ($moreFromVendor as $item)
                    <a href="{{ route('shop.products.show', $item) }}" class="group bg-[#171717] border border-gray-800 rounded-xl overflow-hidden flex flex-col hover:border-gray-700 hover:-translate-y-1">
                        <div class="aspect-square bg-gray-900 overflow-hidden">
                            @if ($item->image)
                                <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-gray-800 to-gray-900 flex items-center justify-center text-4xl text-gray-700 font-black">{{ Str::substr($item->name, 0, 1) }}</div>
                            @endif
                        </div>
                        <div class="p-3 flex-1 flex flex-col">
                            <h3 class="text-sm font-bold text-white line-clamp-2">{{ $item->name }}</h3>
                            <div class="mt-auto pt-2 font-black text-lime-400 text-sm">{{ number_format((float) $item->price, 2) }} <span class="text-gray-500 text-xs">LYD</span></div>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif
    <div class="h-24 md:hidden"></div>

    <div class="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-[#0a0a0a]/95 backdrop-blur-md border-t border-gray-800 p-3">
        <div class="max-w-7xl mx-auto flex items-center gap-3">
            <div class="flex-1 min-w-0">
                <div class="text-[10px] font-black uppercase tracking-widest text-gray-500">Price</div>
                <div class="text-lg font-black text-lime-400">{{ number_format((float) $product->price, 2) }} <span class="text-xs text-gray-500">LYD</span></div>
            </div>
            @if ($inStock)
                <form method="POST" action="{{ route('shop.cart.add', $product) }}" class="flex-1">
                    @csrf
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="w-full bg-lime-400 hover:bg-lime-300 text-black font-black py-3 rounded-lg shadow-[0_0_25px_rgba(163,230,53,0.3)] text-sm">Add to cart</button>
                </form>
            @else
                <button disabled class="flex-1 bg-gray-700 text-gray-400 font-black py-3 rounded-lg text-sm cursor-not-allowed">Out of stock</button>
            @endif
        </div>
    </div>
</x-layouts.shop>