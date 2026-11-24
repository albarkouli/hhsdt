<x-layouts.shop :title="'WESALKOM'">

    {{-- HERO --}}
    <section class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-gray-900 via-gray-950 to-black border border-gray-800 mb-16">
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-96 h-96 rounded-full bg-lime-400/20 blur-[120px]"></div>
            <div class="absolute -bottom-40 -left-40 w-96 h-96 rounded-full bg-purple-500/10 blur-[120px]"></div>
        </div>

        <div class="relative px-6 sm:px-12 py-16 sm:py-28 max-w-5xl">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-lime-400/10 border border-lime-400/30 text-xs font-bold uppercase tracking-widest text-lime-400 mb-6">
                <span class="w-1.5 h-1.5 rounded-full bg-lime-400 animate-pulse"></span>
                Libya's #1 Marketplace
            </span>

            <h1 class="text-5xl sm:text-7xl font-black leading-[0.95] mb-6 text-white">
                Shop bold.<br>
                <span class="bg-gradient-to-r from-lime-400 to-emerald-400 bg-clip-text text-transparent">Buy local.</span>
            </h1>

            <p class="text-lg sm:text-xl text-gray-400 mb-10 max-w-2xl leading-relaxed">
                Thousands of products from trusted Libyan vendors. Cash on delivery, real reviews, zero risk.
            </p>

            <div class="flex flex-wrap gap-3 mb-12">
                <a href="{{ route('shop.products.index') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-lime-400 hover:bg-lime-300 text-black font-black rounded-xl text-sm shadow-[0_0_40px_rgba(163,230,53,0.3)] hover:shadow-[0_0_60px_rgba(163,230,53,0.5)]">
                    Start shopping
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
                @guest
                    <a href="{{ route('vendor.register') }}" class="inline-flex items-center gap-2 px-8 py-4 border-2 border-gray-700 text-white font-bold rounded-xl text-sm hover:border-lime-400 hover:text-lime-400">
                        Sell on WESALKOM
                    </a>
                @endguest
            </div>

            @if ($stats['products'] > 0)
                <div class="grid grid-cols-3 gap-6 max-w-lg pt-8 border-t border-gray-800">
                    <div>
                        <div class="text-3xl sm:text-4xl font-black text-white">{{ $stats['products'] }}<span class="text-lime-400">+</span></div>
                        <div class="text-xs text-gray-500 uppercase tracking-widest mt-1">Products</div>
                    </div>
                    <div>
                        <div class="text-3xl sm:text-4xl font-black text-white">{{ $stats['vendors'] }}<span class="text-lime-400">+</span></div>
                        <div class="text-xs text-gray-500 uppercase tracking-widest mt-1">Vendors</div>
                    </div>
                    <div>
                        <div class="text-3xl sm:text-4xl font-black text-white">{{ $stats['categories'] }}</div>
                        <div class="text-xs text-gray-500 uppercase tracking-widest mt-1">Categories</div>
                    </div>
                </div>
            @endif
        </div>
    </section>
    {{-- CATEGORIES --}}
    @if ($categories->isNotEmpty())
        <section class="mb-16">
            <div class="flex items-end justify-between mb-6">
                <div>
                    <span class="text-xs uppercase tracking-widest text-lime-400 font-black mb-2 block">Browse</span>
                    <h2 class="text-3xl sm:text-4xl font-black text-white">Shop by category</h2>
                </div>
                <a href="{{ route('shop.products.index') }}" class="text-sm font-bold text-gray-400 hover:text-lime-400">View all &rarr;</a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-3">
                @foreach ($categories as $category)
                    <a href="{{ route('shop.products.index', ['category' => $category->slug]) }}" class="group bg-[#171717] border border-gray-800 rounded-xl p-4 text-center hover:border-lime-400/50 hover:-translate-y-1 hover:shadow-[0_8px_30px_rgba(163,230,53,0.1)]">
                        <div class="w-12 h-12 rounded-lg bg-gray-900 border border-gray-800 text-lime-400 flex items-center justify-center mx-auto mb-3 text-lg font-black group-hover:bg-lime-400 group-hover:text-black group-hover:border-lime-400">
                            {{ Str::substr($category->name, 0, 1) }}
                        </div>
                        <div class="text-xs font-bold text-gray-200 line-clamp-2 leading-tight">{{ $category->name }}</div>
                        <div class="text-[10px] text-gray-500 mt-1">{{ $category->products_count }}</div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- NEW ARRIVALS --}}
    @if ($featuredProducts->isNotEmpty())
        <section class="mb-16">
            <div class="flex items-end justify-between mb-6">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-lime-400 animate-pulse"></span>
                        <span class="text-xs uppercase tracking-widest text-lime-400 font-black">Just arrived</span>
                    </div>
                    <h2 class="text-3xl sm:text-4xl font-black text-white">New arrivals</h2>
                </div>
                <a href="{{ route('shop.products.index') }}" class="text-sm font-bold text-gray-400 hover:text-lime-400">Browse all &rarr;</a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach ($featuredProducts as $product)
                    <a href="{{ route('shop.products.show', $product) }}" class="group bg-[#171717] border border-gray-800 rounded-xl overflow-hidden flex flex-col hover:border-gray-700 hover:-translate-y-1 hover:shadow-2xl">
                        <div class="relative overflow-hidden bg-gray-900 aspect-square">
                            @if ($product->image)
                                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-gray-800 to-gray-900 flex items-center justify-center text-6xl text-gray-700 font-black">
                                    {{ Str::substr($product->name, 0, 1) }}
                                </div>
                            @endif
                            @if ($product->stock > 0 && $product->stock < 5)
                                <span class="absolute top-2 right-2 bg-orange-500 text-white text-[10px] font-black uppercase tracking-wider px-2 py-1 rounded">Only {{ $product->stock }} left</span>
                            @endif
                        </div>
                        <div class="p-3 flex-1 flex flex-col">
                            <h3 class="font-bold text-white text-sm line-clamp-2 leading-snug">{{ $product->name }}</h3>
                            <p class="text-xs text-gray-500 mt-1">{{ $product->vendor->name }}</p>
                            @if ($product->reviews_count > 0)
                                <div class="flex items-center gap-1 mt-1.5">
                                    <span class="text-lime-400 text-xs">&#9733;</span>
                                    <span class="text-xs font-bold text-white">{{ number_format($product->avg_rating, 1) }}</span>
                                    <span class="text-xs text-gray-500">({{ $product->reviews_count }})</span>
                                </div>
                            @endif
                            <div class="mt-auto pt-2.5">
                                <span class="font-black text-lime-400 text-base">{{ number_format((float) $product->price, 2) }}</span>
                                <span class="text-xs text-gray-500 ml-1">LYD</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif
    {{-- VENDORS --}}
    @if ($vendors->isNotEmpty())
        <section class="mb-16">
            <div class="mb-6">
                <span class="text-xs uppercase tracking-widest text-lime-400 font-black mb-2 block">Discover</span>
                <h2 class="text-3xl sm:text-4xl font-black text-white">Featured stores</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach ($vendors as $vendor)
                    <a href="{{ route('shop.stores.show', ['vendor' => $vendor->slug]) }}" class="group bg-[#171717] border border-gray-800 rounded-xl p-5 hover:border-lime-400/50 hover:-translate-y-1 hover:shadow-[0_8px_30px_rgba(163,230,53,0.08)]">
                        <div class="flex items-center gap-3 mb-3">
                            @if ($vendor->logo)
                                <img src="{{ Storage::url($vendor->logo) }}" alt="{{ $vendor->name }}" class="w-14 h-14 rounded-xl object-cover border-2 border-gray-800 group-hover:border-lime-400/50">
                            @else
                                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-lime-400 to-emerald-500 flex items-center justify-center text-black text-xl font-black">
                                    {{ Str::substr($vendor->name, 0, 1) }}
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <div class="font-black text-white truncate">{{ $vendor->name }}</div>
                                @if ($vendor->city)
                                    <div class="text-xs text-gray-500">{{ $vendor->city }}</div>
                                @endif
                            </div>
                        </div>
                        @if ($vendor->description)
                            <p class="text-xs text-gray-400 line-clamp-2 mb-3 leading-relaxed">{{ $vendor->description }}</p>
                        @endif
                        <div class="text-xs font-bold text-lime-400 group-hover:text-lime-300">{{ $vendor->products_count }} products &rarr;</div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- CTA --}}
    <section class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-lime-400 via-lime-500 to-emerald-500 mb-4">
        <div class="absolute inset-0 opacity-20">
            <div class="absolute -top-40 -right-40 w-96 h-96 rounded-full bg-white blur-[120px]"></div>
        </div>
        <div class="relative grid grid-cols-1 md:grid-cols-2 gap-8 p-8 md:p-14 items-center">
            <div>
                <span class="inline-block text-xs uppercase tracking-widest font-black text-black/70 mb-3">For vendors</span>
                <h2 class="text-3xl sm:text-5xl font-black text-black leading-[0.95] mb-4">
                    Turn your shop<br>into an empire.
                </h2>
                <p class="text-black/80 mb-6 text-sm leading-relaxed">
                    Join Libya's fastest-growing marketplace. Zero setup fees. Full control over your products, orders, and pricing.
                </p>
                <ul class="space-y-2 text-sm text-black font-semibold mb-8">
                    <li class="flex items-center gap-2"><span class="w-5 h-5 rounded-full bg-black/20 flex items-center justify-center text-xs">&#10003;</span> Free to open your store</li>
                    <li class="flex items-center gap-2"><span class="w-5 h-5 rounded-full bg-black/20 flex items-center justify-center text-xs">&#10003;</span> Cash on delivery built-in</li>
                    <li class="flex items-center gap-2"><span class="w-5 h-5 rounded-full bg-black/20 flex items-center justify-center text-xs">&#10003;</span> Direct contact with customers</li>
                </ul>
                <a href="{{ auth()->check() && ! auth()->user()->vendor ? route('vendor.register') : route('register') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-black text-white font-black rounded-xl text-sm hover:bg-gray-900 shadow-xl">
                    Open your store
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
            <div class="hidden md:flex items-center justify-center">
                <div class="w-64 h-64 rounded-3xl bg-black/10 backdrop-blur border-2 border-black/20 flex items-center justify-center">
                    <svg class="w-40 h-40 text-black" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </section>


        {{-- RECENTLY VIEWED --}}
    @if (isset($recentlyViewed) && $recentlyViewed->isNotEmpty())
        <section class="mb-16">
            <div class="flex items-end justify-between mb-6">
                <div>
                    <span class="text-xs uppercase tracking-widest text-lime-400 font-black mb-2 block">Continue browsing</span>
                    <h2 class="text-3xl sm:text-4xl font-black text-white">Recently viewed</h2>
                </div>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                @foreach ($recentlyViewed as $recentProduct)
                    <a href="{{ route('shop.products.show', $recentProduct) }}" class="group bg-[#171717] border border-gray-800 rounded-xl overflow-hidden flex flex-col hover:border-gray-700 hover:-translate-y-1 transition">
                        <div class="aspect-square bg-gray-900 overflow-hidden">
                            @if ($recentProduct->image)
                                <img src="{{ Storage::url($recentProduct->image) }}" alt="{{ $recentProduct->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-gray-800 to-gray-900 flex items-center justify-center text-5xl text-gray-700 font-black">{{ Str::substr($recentProduct->name, 0, 1) }}</div>
                            @endif
                        </div>
                        <div class="p-3 flex-1 flex flex-col">
                            <h3 class="font-bold text-white text-sm line-clamp-2 leading-snug">{{ $recentProduct->name }}</h3>
                            <p class="text-xs text-gray-500 mt-1">{{ $recentProduct->vendor->name }}</p>
                            <div class="mt-auto pt-2.5">
                                <span class="font-black text-lime-400 text-base">{{ number_format((float) $recentProduct->price, 2) }}</span>
                                <span class="text-xs text-gray-500 ml-1">LYD</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

</x-layouts.shop>