<x-layouts.dashboard :title="'My Products'">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-black text-[#D6DEE6]">My Products</h1>
                <p class="text-sm text-[#A8B5C1] mt-1">{{ $vendor->name }}</p>
            </div>
            @if ($vendor->status->canSell())
                <a href="{{ route('vendor.products.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gold hover:bg-gold-hover text-[#D6DEE6] font-black rounded-lg text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    New product
                </a>
            @endif
        </div>

        @unless ($vendor->status->canSell())
            <div class="rounded-xl border border-yellow-500/30 bg-yellow-500/10 p-4 text-sm text-yellow-300">Your store is not active. You cannot add or edit products until approved.</div>
        @endunless

        @if (session('status'))
            <div class="rounded-xl border border-gold/30 bg-gold/10 p-3 text-sm text-gold-hover">{{ session('status') }}</div>
        @endif

        <div class="bg-[#171717] border border-[#344453] rounded-2xl overflow-hidden">
            <table class="min-w-full divide-y divide-gray-800">
                <thead class="bg-[#263543]/50">
                    <tr>
                        <th class="px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-[#A8B5C1]">Image</th>
                        <th class="px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-[#A8B5C1]">Product</th>
                        <th class="px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-[#A8B5C1]">Category</th>
                        <th class="px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-[#A8B5C1]">Price</th>
                        <th class="px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-[#A8B5C1]">Stock</th>
                        <th class="px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-[#A8B5C1]">Status</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @forelse ($products as $product)
                        <tr class="hover:bg-[#263543]/30">
                            <td class="px-4 py-2">
                                @if ($product->image)
                                    <img src="{{ Storage::url($product->image) }}" alt="" class="w-12 h-12 object-cover rounded-lg">
                                @else
                                    <div class="w-12 h-12 bg-[#263543] rounded-lg flex items-center justify-center text-[#A8B5C1] font-black">{{ Str::substr($product->name, 0, 1) }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-2">
                                <div class="font-bold text-[#D6DEE6] text-sm">{{ $product->name }}</div>
                                @if ($product->sku)<div class="text-[10px] text-[#A8B5C1]">SKU: {{ $product->sku }}</div>@endif
                            </td>
                            <td class="px-4 py-2 text-sm text-[#A8B5C1]">{{ $product->category?->name ?? 'أ¢â‚¬â€‌' }}</td>
                            <td class="px-4 py-2 text-sm font-black text-gold">{{ number_format((float) $product->price, 2) }} <span class="text-[10px] text-[#A8B5C1]">LYD</span></td>
                            <td class="px-4 py-2 text-sm text-[#D6DEE6]">{{ $product->stock }}</td>
                            <td class="px-4 py-2">
                                @if ($product->is_active)
                                    <span class="px-2 py-0.5 text-[10px] font-black uppercase tracking-wider rounded bg-gold/10 text-gold border border-gold/30">Active</span>
                                @else
                                    <span class="px-2 py-0.5 text-[10px] font-black uppercase tracking-wider rounded bg-gray-700/50 text-[#A8B5C1] border border-[#344453]">Inactive</span>
                                @endif
                            </td>
                            <td class="px-4 py-2">
                                <div class="flex gap-1 justify-end">
                                    @if ($vendor->status->canSell())
                                        <a href="{{ route('vendor.products.edit', $product) }}" class="px-2 py-1 text-[10px] font-black uppercase bg-gold text-[#D6DEE6] rounded hover:bg-gold-hover">Edit</a>
                                        <form method="POST" action="{{ route('vendor.products.destroy', $product) }}" onsubmit="return confirm('Delete this product?')">
                                            @csrf @method('DELETE')
                                            <button class="px-2 py-1 text-[10px] font-black uppercase bg-red-500/20 text-red-400 border border-red-500/30 rounded hover:bg-red-500/30">Del</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-4 py-8 text-center text-sm text-[#A8B5C1]">No products yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div>{{ $products->links() }}</div>
    </div>
</x-layouts.dashboard>