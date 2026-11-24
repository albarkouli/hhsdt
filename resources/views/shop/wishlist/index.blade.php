<x-layouts.shop :title="'My Wishlist'">
    <div class="mb-8">
        <h1 class="text-3xl sm:text-4xl font-black text-[#D6DEE6]">My Wishlist</h1>
        <p class="text-sm text-[#A8B5C1] mt-1">{{ $products->total() }} item{{ $products->total() === 1 ? '' : 's' }} saved</p>
    </div>

    @if ($products->isEmpty())
        <div class="bg-[#171717] border border-[#344453] rounded-2xl p-16 text-center">
            <div class="w-20 h-20 rounded-full bg-[#263543] flex items-center justify-center mx-auto mb-5">
                <svg class="w-10 h-10 text-[#A8B5C1]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            </div>
            <h3 class="text-xl font-black text-[#D6DEE6] mb-2">Your wishlist is empty</h3>
            <p class="text-sm text-[#A8B5C1] mb-6">Save products you love to find them later</p>
            <a href="{{ route('shop.products.index') }}" class="inline-block px-8 py-3.5 bg-gold text-[#D6DEE6] font-black rounded-xl hover:bg-gold-hover">Browse products</a>
        </div>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach ($products as $product)
                <div class="group bg-[#171717] border border-[#344453] rounded-xl overflow-hidden flex flex-col hover:border-[#344453] hover:-translate-y-1 relative">
                    <form method="POST" action="{{ route('shop.wishlist.toggle', $product) }}" class="absolute top-2 right-2 z-10">
                        @csrf
                        <button type="submit" class="w-9 h-9 rounded-full bg-[#17212B]/80 backdrop-blur border border-[#344453] flex items-center justify-center text-red-400 hover:text-red-300 hover:bg-[#17212B]" title="Remove">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/></svg>
                        </button>
                    </form>

                    <a href="{{ route('shop.products.show', $product) }}" class="flex-1 flex flex-col">
                        <div class="aspect-square bg-[#263543] overflow-hidden">
                            @if ($product->image)
                                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-gray-800 to-[#17212B] flex items-center justify-center text-5xl text-[#A8B5C1] font-black">{{ Str::substr($product->name, 0, 1) }}</div>
                            @endif
                        </div>
                        <div class="p-3 flex-1 flex flex-col">
                            <h3 class="font-bold text-[#D6DEE6] text-sm line-clamp-2 leading-snug">{{ $product->name }}</h3>
                            <p class="text-xs text-[#A8B5C1] mt-1">{{ $product->vendor->name }}</p>
                            <div class="mt-auto pt-2.5">
                                <span class="font-black text-gold text-base">{{ number_format((float) $product->price, 2) }}</span>
                                <span class="text-xs text-[#A8B5C1] ml-1">LYD</span>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
        <div class="mt-8">{{ $products->links() }}</div>
    @endif
</x-layouts.shop>