<x-layouts.shop :title="$vendor->name">
    <nav class="text-xs font-semibold text-[#A8B5C1] mb-6 flex items-center flex-wrap gap-1">
        <a href="{{ route('home') }}" class="hover:text-gold">Home</a>
        <span class="text-[#A8B5C1]">/</span>
        <a href="{{ route('shop.products.index') }}" class="hover:text-gold">Shop</a>
        <span class="text-[#A8B5C1]">/</span>
        <span class="text-[#D6DEE6]">{{ $vendor->name }}</span>
    </nav>

    {{-- Store header --}}
    <div class="bg-[#171717] border border-[#344453] rounded-2xl p-6 md:p-8 mb-8 relative overflow-hidden">
        <div class="absolute -top-20 -right-20 w-64 h-64 rounded-full bg-gold/10 blur-3xl"></div>
        <div class="relative flex items-start gap-6 flex-wrap">
            @if ($vendor->logo)
                <img src="{{ Storage::url($vendor->logo) }}" alt="{{ $vendor->name }}" class="w-20 h-20 rounded-2xl object-cover border-2 border-[#344453] shrink-0">
            @else
                <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-gold to-gold-hover flex items-center justify-center text-[#D6DEE6] text-3xl font-black shrink-0">{{ Str::substr($vendor->name, 0, 1) }}</div>
            @endif
            <div class="flex-1 min-w-[200px]">
                <h1 class="text-2xl sm:text-3xl font-black text-[#D6DEE6]">{{ $vendor->name }}</h1>
                @if ($vendor->description)
                    <p class="text-sm text-[#A8B5C1] mt-2 max-w-2xl leading-relaxed">{{ $vendor->description }}</p>
                @endif
                <div class="mt-4 flex flex-wrap items-center gap-3 text-sm text-[#A8B5C1]">
                    @if ($vendor->city)
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                            {{ $vendor->city }}
                        </span>
                    @endif
                    @if ($vendor->phone)
                        <span class="flex items-center gap-1.5" dir="ltr">
                            <svg class="w-4 h-4 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            {{ $vendor->phone }}
                        </span>
                    @endif
                    @if ($vendor->email)
                        <a href="mailto:{{ $vendor->email }}" class="flex items-center gap-1.5 hover:text-gold">
                            <svg class="w-4 h-4 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            {{ $vendor->email }}
                        </a>
                    @endif
                </div>
            </div>
            <div class="text-right shrink-0">
                <div class="text-4xl font-black text-gold">{{ $products->total() }}</div>
                <div class="text-[10px] font-black uppercase tracking-widest text-[#A8B5C1]">Products</div>
            </div>
        </div>
    </div>

    {{-- Products --}}
    @if ($products->isEmpty())
        <div class="bg-[#171717] border border-[#344453] rounded-2xl p-16 text-center">
            <p class="text-[#A8B5C1] mb-4">This store has no products yet.</p>
            <a href="{{ route('shop.products.index') }}" class="inline-block px-6 py-2.5 bg-gold text-[#D6DEE6] font-black rounded-lg text-sm hover:bg-gold-hover">Browse other stores</a>
        </div>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach ($products as $product)
                <a href="{{ route('shop.products.show', $product) }}" class="group bg-[#171717] border border-[#344453] rounded-xl overflow-hidden flex flex-col hover:border-[#344453] hover:-translate-y-1 transition">
                    <div class="aspect-square bg-[#263543] overflow-hidden">
                        @if ($product->image)
                            <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-gray-800 to-[#17212B] flex items-center justify-center text-5xl text-[#A8B5C1] font-black">{{ Str::substr($product->name, 0, 1) }}</div>
                        @endif
                    </div>
                    <div class="p-3 flex-1 flex flex-col">
                        <h3 class="font-bold text-[#D6DEE6] text-sm line-clamp-2 leading-snug">{{ $product->name }}</h3>
                        @if ($product->category)
                            <p class="text-[10px] text-[#A8B5C1] mt-1 uppercase tracking-wider">{{ $product->category->name }}</p>
                        @endif
                        <div class="mt-auto pt-2.5 flex items-center justify-between">
                            <span class="font-black text-gold text-base">{{ number_format((float) $product->price, 2) }} <span class="text-[10px] text-[#A8B5C1]">LYD</span></span>
                            @if ($product->stock > 0 && $product->stock < 5)
                                <span class="text-[10px] text-orange-400 font-black">{{ $product->stock }} left</span>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        <div class="mt-8">{{ $products->links() }}</div>
    @endif
</x-layouts.shop>