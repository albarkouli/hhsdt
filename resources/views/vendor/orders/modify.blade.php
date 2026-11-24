<x-layouts.dashboard :title="'Modify Order'">
    <div class="max-w-3xl mx-auto space-y-6">
        <div>
            <a href="{{ route('vendor.orders.show', $order) }}" class="text-xs font-bold text-[#A8B5C1] hover:text-gold">&larr; Back to order</a>
            <h1 class="text-3xl font-black text-[#D6DEE6] mt-1">Propose changes أ¢â‚¬â€‌ {{ $order->order_number }}</h1>
            <p class="text-sm text-[#A8B5C1] mt-1">Customer will accept or reject. Order will be on hold.</p>
        </div>

        @if ($errors->any())
            <div class="rounded-xl border border-red-500/30 bg-red-500/10 p-3 text-sm text-red-300">{{ $errors->first() }}</div>
        @endif

        <div class="rounded-xl border border-indigo-500/30 bg-indigo-500/10 p-4 text-sm text-indigo-300">
            <strong class="text-indigo-200">How it works:</strong> Adjust quantities below (0 = remove item). Current stock is released immediately. If customer accepts, new quantities are deducted. If rejected, order is cancelled.
        </div>

        <form method="POST" action="{{ route('vendor.orders.modify', $order) }}" class="space-y-5">
            @csrf

            <div class="bg-[#171717] border border-[#344453] rounded-2xl overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-[#263543]/50">
                        <tr class="text-[10px] font-black uppercase tracking-widest text-[#A8B5C1]">
                            <th class="px-4 py-3 text-right">Product</th>
                            <th class="px-4 py-3 text-right">Price</th>
                            <th class="px-4 py-3 text-right">Ordered</th>
                            <th class="px-4 py-3 text-right">Available</th>
                            <th class="px-4 py-3 text-right">New qty</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @foreach ($order->items as $item)
                            <tr>
                                <td class="px-4 py-3 text-[#D6DEE6]">{{ $item->product_name }}</td>
                                <td class="px-4 py-3 text-[#A8B5C1]">{{ number_format((float) $item->price, 2) }} LYD</td>
                                <td class="px-4 py-3 font-bold text-[#D6DEE6]">{{ $item->quantity }}</td>
                                <td class="px-4 py-3 text-[#A8B5C1]">{{ $item->product?->stock ?? 'أ¢â‚¬â€‌' }}</td>
                                <td class="px-4 py-3">
                                    <input type="hidden" name="items[{{ $loop->index }}][id]" value="{{ $item->id }}">
                                    <input type="number" name="items[{{ $loop->index }}][quantity]" value="{{ $item->quantity }}" min="0" max="{{ $item->quantity }}"
                                           class="w-20 rounded-lg bg-[#263543] border border-[#344453] text-[#D6DEE6] text-sm text-center font-black py-1.5 focus:border-gold">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="bg-[#171717] border border-[#344453] rounded-2xl p-5">
                <label class="block text-xs font-black uppercase tracking-widest text-[#A8B5C1] mb-2">Reason for change *</label>
                <textarea name="note" rows="3" required minlength="5" maxlength="500"
                          placeholder="e.g. Only 2 units available in stock أ¢â‚¬â€‌ please confirm if you accept 2 instead of 3."
                          class="w-full rounded-lg bg-[#263543] border border-[#344453] text-[#D6DEE6] text-sm px-3 py-2.5 focus:border-gold">{{ old('note') }}</textarea>
                @error('note')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                <p class="mt-1 text-xs text-[#A8B5C1]">This message will be shown to the customer.</p>
            </div>

            <div class="flex items-center justify-between">
                <a href="{{ route('vendor.orders.show', $order) }}" class="text-sm font-bold text-[#A8B5C1] hover:text-gold">Cancel</a>
                <button type="submit" class="px-6 py-2.5 bg-gold hover:bg-gold-hover text-[#D6DEE6] font-black rounded-lg text-sm">Send proposal</button>
            </div>
        </form>
    </div>
</x-layouts.dashboard>