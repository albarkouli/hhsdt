<x-layouts.shop :title="'wesalkom | وصـــــــــالكم'">
@php $isAr = app()->getLocale() === 'ar'; @endphp

{{-- HERO --}}
<section class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-[#1F2A36] via-[#17212B] to-[#17212B] border border-[#344453] mb-16">
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-96 h-96 rounded-full bg-gold/10 blur-[120px]"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 rounded-full bg-blue/10 blur-[120px]"></div>
    </div>

    <div class="relative px-6 sm:px-12 py-16 sm:py-28 max-w-5xl">
        <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-gold/10 border border-gold/30 text-xs font-bold uppercase tracking-widest text-gold mb-6">
            <span class="w-1.5 h-1.5 rounded-full bg-gold animate-pulse"></span>
            {{ $isAr ? 'كل ما تحب، أقرب إليك' : 'Everything You Love, Closer.' }}
        </span>

        <h1 class="text-5xl sm:text-7xl font-black leading-[0.95] mb-6 text-[#D6DEE6]">
            @if ($isAr)
                كل ما تحب،<br>
                <span class="bg-gradient-to-r from-gold to-[#E3C06A] bg-clip-text text-transparent">أقرب إليك.</span>
            @else
                Everything You Love,<br>
                <span class="bg-gradient-to-r from-gold to-[#E3C06A] bg-clip-text text-transparent">Closer.</span>
            @endif
        </h1>

        <p class="text-lg sm:text-xl text-[#A8B5C1] mb-10 max-w-2xl leading-relaxed">
            {{ $isAr
                ? 'اكتشف منتجات ومتاجر متنوعة، واستمتع بتجربة تسوق تجمع لك كل ما تحب في مكان واحد.'
                : 'Discover stores and products in one place with a seamless shopping experience.' }}
        </p>

        <div class="flex flex-wrap gap-3 mb-12">
            <a href="{{ route('shop.products.index') }}"
               class="inline-flex items-center gap-2 px-8 py-4 bg-gold hover:bg-gold-hover text-[#17212B] font-black rounded-xl text-sm shadow-[0_0_40px_rgba(217,164,65,0.3)] hover:shadow-[0_0_60px_rgba(217,164,65,0.5)] transition">
                {{ $isAr ? 'ابدأ التسوق' : 'Start shopping' }}
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
            @guest
                <a href="{{ route('vendor.register') }}"
                   class="inline-flex items-center gap-2 px-8 py-4 border-2 border-[#344453] text-[#D6DEE6] font-bold rounded-xl text-sm hover:border-gold hover:text-gold transition">
                    {{ $isAr ? 'بِع على وصالكم' : 'Sell on wesalkom' }}
                </a>
            @endguest
        </div>

        @if ($stats['products'] > 0)
            <div class="grid grid-cols-3 gap-6 max-w-lg pt-8 border-t border-[#344453]">
                <div>
                    <div class="text-3xl sm:text-4xl font-black text-[#D6DEE6]">{{ $stats['products'] }}<span class="text-gold">+</span></div>
                    <div class="text-xs text-[#A8B5C1] uppercase tracking-widest mt-1">{{ $isAr ? 'منتج' : 'Products' }}</div>
                </div>
                <div>
                    <div class="text-3xl sm:text-4xl font-black text-[#D6DEE6]">{{ $stats['vendors'] }}<span class="text-gold">+</span></div>
                    <div class="text-xs text-[#A8B5C1] uppercase tracking-widest mt-1">{{ $isAr ? 'متجر' : 'Vendors' }}</div>
                </div>
                <div>
                    <div class="text-3xl sm:text-4xl font-black text-[#D6DEE6]">{{ $stats['categories'] }}</div>
                    <div class="text-xs text-[#A8B5C1] uppercase tracking-widest mt-1">{{ $isAr ? 'تصنيف' : 'Categories' }}</div>
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
                <span class="text-xs uppercase tracking-widest text-gold font-black mb-2 block">{{ $isAr ? 'تصفح' : 'Browse' }}</span>
                <h2 class="text-3xl sm:text-4xl font-black text-[#D6DEE6]">{{ $isAr ? 'تسوّق حسب التصنيف' : 'Shop by category' }}</h2>
            </div>
            <a href="{{ route('shop.products.index') }}" class="text-sm font-bold text-[#A8B5C1] hover:text-gold transition">
                {{ $isAr ? '← عرض الكل' : 'View all →' }}
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-3">
            @foreach ($categories as $category)
                <a href="{{ route('shop.products.index', ['category' => $category->slug]) }}"
                   class="group bg-[#263543] border border-[#344453] rounded-xl p-4 text-center hover:border-gold/50 hover:-translate-y-1 hover:shadow-[0_8px_30px_rgba(217,164,65,0.1)] transition">
                    <div class="w-12 h-12 rounded-lg bg-[#1F2A36] border border-[#344453] text-gold flex items-center justify-center mx-auto mb-3 text-lg font-black group-hover:bg-gold group-hover:text-[#17212B] group-hover:border-gold transition">
                        {{ Str::substr($category->name, 0, 1) }}
                    </div>
                    <div class="text-xs font-bold text-[#D6DEE6] line-clamp-2 leading-tight">{{ $category->name }}</div>
                    <div class="text-[10px] text-[#A8B5C1] mt-1">{{ $category->products_count }}</div>
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
                    <span class="w-1.5 h-1.5 rounded-full bg-gold animate-pulse"></span>
                    <span class="text-xs uppercase tracking-widest text-gold font-black">{{ $isAr ? 'وصل حديثًا' : 'Just arrived' }}</span>
                </div>
                <h2 class="text-3xl sm:text-4xl font-black text-[#D6DEE6]">{{ $isAr ? 'أحدث المنتجات' : 'New arrivals' }}</h2>
            </div>
            <a href="{{ route('shop.products.index') }}" class="text-sm font-bold text-[#A8B5C1] hover:text-gold transition">
                {{ $isAr ? '← تصفح الكل' : 'Browse all →' }}
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach ($featuredProducts as $product)
                <a href="{{ route('shop.products.show', $product) }}"
                   class="group bg-[#263543] border border-[#344453] rounded-xl overflow-hidden flex flex-col hover:border-gold/50 hover:-translate-y-1 hover:shadow-2xl transition">
                    <div class="relative overflow-hidden bg-[#1F2A36] aspect-square">
                        @if ($product->image)
                            <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-[#1F2A36] to-[#263543] flex items-center justify-center text-6xl text-gold font-black">
                                {{ Str::substr($product->name, 0, 1) }}
                            </div>
                        @endif
                        @if ($product->stock > 0 && $product->stock < 5)
                            <span class="absolute top-2 right-2 bg-warning text-[#17212B] text-[10px] font-black uppercase tracking-wider px-2 py-1 rounded">
                                {{ $isAr ? 'بقى ' . $product->stock : 'Only ' . $product->stock . ' left' }}
                            </span>
                        @endif
                    </div>
                    <div class="p-3 flex-1 flex flex-col">
                        <h3 class="font-bold text-[#D6DEE6] text-sm line-clamp-2 leading-snug">{{ $product->name }}</h3>
                        <p class="text-xs text-[#A8B5C1] mt-1">{{ $product->vendor->name }}</p>
                        @if ($product->reviews_count > 0)
                            <div class="flex items-center gap-1 mt-1.5">
                                <span class="text-gold text-xs">&#9733;</span>
                                <span class="text-xs font-bold text-[#D6DEE6]">{{ number_format($product->avg_rating, 1) }}</span>
                                <span class="text-xs text-[#A8B5C1]">({{ $product->reviews_count }})</span>
                            </div>
                        @endif
                        <div class="mt-auto pt-2.5">
                            <span class="font-black text-gold text-base">{{ number_format((float) $product->price, 2) }}</span>
                            <span class="text-xs text-[#A8B5C1] ms-1">{{ $isAr ? 'د.ل' : 'LYD' }}</span>
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
            <span class="text-xs uppercase tracking-widest text-gold font-black mb-2 block">{{ $isAr ? 'اكتشف' : 'Discover' }}</span>
            <h2 class="text-3xl sm:text-4xl font-black text-[#D6DEE6]">{{ $isAr ? 'متاجر مميزة' : 'Featured stores' }}</h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach ($vendors as $vendor)
                <a href="{{ route('shop.stores.show', ['vendor' => $vendor->slug]) }}"
                   class="group bg-[#263543] border border-[#344453] rounded-xl p-5 hover:border-gold/50 hover:-translate-y-1 hover:shadow-[0_8px_30px_rgba(217,164,65,0.08)] transition">
                    <div class="flex items-center gap-3 mb-3">
                        @if ($vendor->logo)
                            <img src="{{ Storage::url($vendor->logo) }}" alt="{{ $vendor->name }}" class="w-14 h-14 rounded-xl object-cover border-2 border-[#344453] group-hover:border-gold/50 transition">
                        @else
                            <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-gold to-gold-hover flex items-center justify-center text-[#17212B] text-xl font-black">
                                {{ Str::substr($vendor->name, 0, 1) }}
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <div class="font-black text-[#D6DEE6] truncate">{{ $vendor->name }}</div>
                            @if ($vendor->city)
                                <div class="text-xs text-[#A8B5C1]">{{ $vendor->city }}</div>
                            @endif
                        </div>
                    </div>
                    @if ($vendor->description)
                        <p class="text-xs text-[#A8B5C1] line-clamp-2 mb-3 leading-relaxed">{{ $vendor->description }}</p>
                    @endif
                    <div class="text-xs font-bold text-gold group-hover:text-[#E3C06A] transition">
                        {{ $vendor->products_count }} {{ $isAr ? 'منتج ←' : 'products →' }}
                    </div>
                </a>
            @endforeach
        </div>
    </section>
@endif

{{-- CTA --}}
<section class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-[#1F2A36] via-[#17212B] to-[#17212B] border border-[#344453] mb-4">
    <div class="absolute inset-0 opacity-20">
        <div class="absolute -top-40 -right-40 w-96 h-96 rounded-full bg-gold blur-[120px]"></div>
    </div>
    <div class="relative grid grid-cols-1 md:grid-cols-2 gap-8 p-8 md:p-14 items-center">
        <div>
            <span class="inline-block text-xs uppercase tracking-widest font-black text-gold mb-3">{{ $isAr ? 'للبائعين' : 'For vendors' }}</span>
            <h2 class="text-3xl sm:text-5xl font-black text-[#D6DEE6] leading-[0.95] mb-4">
                @if ($isAr)
                    حوّل متجرك<br>إلى إمبراطورية.
                @else
                    Turn your shop<br>into an empire.
                @endif
            </h2>
            <p class="text-[#A8B5C1] mb-6 text-sm leading-relaxed">
                {{ $isAr
                    ? 'انضم إلى منصة التسوق الأسرع نموًا. بدون رسوم تأسيس. تحكم كامل بمنتجاتك وطلباتك وأسعارك.'
                    : 'Join the fastest-growing marketplace. Zero setup fees. Full control over your products, orders, and pricing.' }}
            </p>
            <ul class="space-y-2 text-sm text-[#D6DEE6] font-semibold mb-8">
                <li class="flex items-center gap-2"><span class="w-5 h-5 rounded-full bg-gold/20 text-gold flex items-center justify-center text-xs">&#10003;</span> {{ $isAr ? 'افتح متجرك مجانًا' : 'Free to open your store' }}</li>
                <li class="flex items-center gap-2"><span class="w-5 h-5 rounded-full bg-gold/20 text-gold flex items-center justify-center text-xs">&#10003;</span> {{ $isAr ? 'الدفع عند الاستلام مدمج' : 'Cash on delivery built-in' }}</li>
                <li class="flex items-center gap-2"><span class="w-5 h-5 rounded-full bg-gold/20 text-gold flex items-center justify-center text-xs">&#10003;</span> {{ $isAr ? 'تواصل مباشر مع العملاء' : 'Direct contact with customers' }}</li>
            </ul>
            <a href="{{ auth()->check() && ! auth()->user()->vendor ? route('vendor.register') : route('register') }}"
               class="inline-flex items-center gap-2 px-8 py-4 bg-gold text-[#17212B] font-black rounded-xl text-sm hover:bg-gold-hover shadow-xl transition">
                {{ $isAr ? 'افتح متجرك' : 'Open your store' }}
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
        <div class="hidden md:flex items-center justify-center">
            <div class="w-64 h-64 rounded-3xl bg-[#17212B]/50 backdrop-blur border-2 border-gold/20 flex items-center justify-center">
                <svg class="w-40 h-40 text-gold" fill="currentColor" viewBox="0 0 24 24">
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
                <span class="text-xs uppercase tracking-widest text-gold font-black mb-2 block">{{ $isAr ? 'تابع التصفح' : 'Continue browsing' }}</span>
                <h2 class="text-3xl sm:text-4xl font-black text-[#D6DEE6]">{{ $isAr ? 'شوهد مؤخرًا' : 'Recently viewed' }}</h2>
            </div>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            @foreach ($recentlyViewed as $recentProduct)
                <a href="{{ route('shop.products.show', $recentProduct) }}"
                   class="group bg-[#263543] border border-[#344453] rounded-xl overflow-hidden flex flex-col hover:border-gold/50 hover:-translate-y-1 transition">
                    <div class="aspect-square bg-[#1F2A36] overflow-hidden">
                        @if ($recentProduct->image)
                            <img src="{{ Storage::url($recentProduct->image) }}" alt="{{ $recentProduct->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-[#1F2A36] to-[#263543] flex items-center justify-center text-5xl text-gold font-black">{{ Str::substr($recentProduct->name, 0, 1) }}</div>
                        @endif
                    </div>
                    <div class="p-3 flex-1 flex flex-col">
                        <h3 class="font-bold text-[#D6DEE6] text-sm line-clamp-2 leading-snug">{{ $recentProduct->name }}</h3>
                        <p class="text-xs text-[#A8B5C1] mt-1">{{ $recentProduct->vendor->name }}</p>
                        <div class="mt-auto pt-2.5">
                            <span class="font-black text-gold text-base">{{ number_format((float) $recentProduct->price, 2) }}</span>
                            <span class="text-xs text-[#A8B5C1] ms-1">{{ $isAr ? 'د.ل' : 'LYD' }}</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
@endif

</x-layouts.shop>