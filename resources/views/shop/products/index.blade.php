<x-layouts.shop :title="'Shop'">
    <div class="shop-grid">

        {{-- SIDEBAR FILTERS --}}
        <aside class="">
            <form method="GET" action="{{ route('shop.products.index') }}" class="bg-[#171717] border border-[#344453] rounded-2xl p-5 space-y-4 md:sticky md:top-32">
                <div class="flex items-center justify-between">
                    <h2 class="font-black text-[#D6DEE6] text-lg">Filters</h2>
                    @if ($hasFilters)
                        <a href="{{ route('shop.products.index') }}" class="text-xs font-bold text-gold hover:text-gold-hover">Clear</a>
                    @endif
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-[#A8B5C1] mb-1.5">Search</label>
                    <input name="q" type="text" value="{{ $q }}" placeholder="Product name..."
                           class="w-full rounded-lg bg-[#263543] border border-[#344453] text-[#D6DEE6] text-sm px-3 py-2 placeholder-gray-600 focus:border-gold focus:ring-2 focus:ring-gold/20">
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-[#A8B5C1] mb-1.5">Category</label>
                    <select name="category" class="w-full rounded-lg bg-[#263543] border border-[#344453] text-[#D6DEE6] text-sm px-3 py-2 focus:border-gold">
                        <option value="">All categories</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->slug }}" @selected($categorySlug === $cat->slug)>{{ $cat->name }} ({{ $cat->products_count }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-[#A8B5C1] mb-1.5">Price (LYD)</label>
                    <div class="grid grid-cols-2 gap-2">
                        <input name="min_price" type="number" min="0" step="0.01" value="{{ $minPrice }}" placeholder="Min" class="w-full rounded-lg bg-[#263543] border border-[#344453] text-[#D6DEE6] text-sm px-2 py-2 placeholder-gray-600 focus:border-gold">
                        <input name="max_price" type="number" min="0" step="0.01" value="{{ $maxPrice }}" placeholder="Max" class="w-full rounded-lg bg-[#263543] border border-[#344453] text-[#D6DEE6] text-sm px-2 py-2 placeholder-gray-600 focus:border-gold">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-[#A8B5C1] mb-1.5">Sort by</label>
                    <select name="sort" class="w-full rounded-lg bg-[#263543] border border-[#344453] text-[#D6DEE6] text-sm px-3 py-2 focus:border-gold">
                        <option value="newest" @selected($sort === 'newest')>Newest</option>
                        <option value="price_asc" @selected($sort === 'price_asc')>Price (Low to High)</option>
                        <option value="price_desc" @selected($sort === 'price_desc')>Price (High to Low)</option>
                        <option value="rating" @selected($sort === 'rating')>Top rated</option>
                    </select>
                </div>

                <label class="flex items-center gap-2 text-sm text-[#D6DEE6] cursor-pointer">
                    <input type="checkbox" name="include_out_of_stock" value="1" @checked($includeOutOfStock) class="w-4 h-4 rounded bg-[#263543] border-[#344453] text-gold focus:ring-gold">
                    <span class="font-semibold">Include out of stock</span>
                </label>

                <button type="submit" class="w-full bg-gold hover:bg-gold-hover text-[#D6DEE6] font-black py-2.5 rounded-lg text-sm">Apply filters</button>
            </form>
        </aside>

        {{-- RESULTS --}}
        <div class="min-w-0">
            <div class="flex items-center justify-between mb-5 flex-wrap gap-2">
                <div>
                    <h1 class="text-2xl font-black text-[#D6DEE6]">
                        {{ $totalCount }} product{{ $totalCount === 1 ? '' : 's' }}
                    </h1>
                    @if ($q !== '')
                        <p class="text-sm text-[#A8B5C1] mt-1">Results for "<span class="text-gold font-bold">{{ $q }}</span>"</p>
                    @endif
                </div>
                @if ($hasFilters)
                    <a href="{{ route('shop.products.index') }}" class="text-xs font-bold text-gold hover:text-gold-hover">Clear filters</a>
                @endif
            </div>

            @if ($products->isEmpty())
                <div class="bg-[#171717] border border-[#344453] rounded-2xl p-16 text-center">
                    <div class="w-16 h-16 rounded-full bg-[#263543] flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-[#A8B5C1]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <h3 class="text-lg font-black text-[#D6DEE6] mb-2">No products found</h3>
                    <p class="text-sm text-[#A8B5C1] mb-5">Try adjusting your filters or search terms.</p>
                    @if ($hasFilters)
                        <a href="{{ route('shop.products.index') }}" class="inline-block px-6 py-2.5 bg-gold text-[#D6DEE6] font-black rounded-lg text-sm hover:bg-gold-hover">Clear all filters</a>
                    @endif
                </div>
            @else
                {{-- Auto-responsive grid --}}
                <div class="grid gap-6" style="grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));">
                    @foreach ($products as $product)
                        <a href="{{ route('shop.products.show', $product) }}" class="group bg-[#171717] border border-[#344453] rounded-xl overflow-hidden flex flex-col hover:border-[#344453] hover:-translate-y-1 transition">
                            <div class="relative overflow-hidden bg-[#263543] aspect-square">
                                @if ($product->image)
                                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-gray-800 to-[#17212B] flex items-center justify-center text-6xl text-[#A8B5C1] font-black">{{ Str::substr($product->name, 0, 1) }}</div>
                                @endif
                                @if ($product->stock == 0)
                                    <span class="absolute top-2 left-2 bg-red-500 text-[#D6DEE6] text-[10px] font-black uppercase tracking-wider px-2 py-1 rounded">Out of stock</span>
                                @elseif ($product->stock < 5)
                                    <span class="absolute top-2 right-2 bg-orange-500 text-[#D6DEE6] text-[10px] font-black uppercase tracking-wider px-2 py-1 rounded">{{ $product->stock }} left</span>
                                @endif
                            </div>
                            <div class="p-4 flex-1 flex flex-col gap-1">
                                <h3 class="font-bold text-[#D6DEE6] text-sm line-clamp-2 leading-snug min-h-[2.5rem]">{{ $product->name }}</h3>
                                <p class="text-xs text-[#A8B5C1]">{{ $product->vendor->name }}</p>
                                @if ($product->reviews_count > 0)
                                    <div class="flex items-center gap-1 mt-0.5">
                                        <svg class="w-3 h-3 text-gold inline-block" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        <span class="text-xs font-bold text-[#D6DEE6]">{{ number_format($product->avg_rating, 1) }}</span>
                                        <span class="text-xs text-[#A8B5C1]">({{ $product->reviews_count }})</span>
                                    </div>
                                @endif

                                <div class="mt-auto pt-2">
                                    <span class="font-black text-gold text-lg">{{ number_format((float) $product->price, 2) }}</span>
                                    <span class="text-xs text-[#A8B5C1] ml-1">LYD</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="mt-8">{{ $products->links() }}</div>
            @endif
        </div>
    </div>
</x-layouts.shop>