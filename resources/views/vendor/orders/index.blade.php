<x-layouts.dashboard :title="'Orders'">
    <div class="space-y-6">
        <div>
            <h1 class="text-3xl font-black text-[#D6DEE6]">Orders</h1>
            <p class="text-sm text-[#A8B5C1] mt-1">{{ $vendor->name }}</p>
        </div>

        @if (session('status'))
            <div class="rounded-xl border border-gold/30 bg-gold/10 p-3 text-sm text-gold-hover">{{ session('status') }}</div>
        @endif
        @if (session('error'))
            <div class="rounded-xl border border-red-500/30 bg-red-500/10 p-3 text-sm text-red-300">{{ session('error') }}</div>
        @endif

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-2">
            @foreach (['pending' => 'Pending', 'pending_customer_approval' => 'Awaiting', 'confirmed' => 'Confirmed', 'shipped' => 'Shipped', 'delivered' => 'Delivered', 'cancelled' => 'Cancelled'] as $key => $label)
                <a href="{{ route('vendor.orders.index', ['status' => $key]) }}"
                   @class([
                       'rounded-xl border p-3 hover:border-gold/30 transition',
                       'border-[#344453] bg-[#171717]' => $status !== $key,
                       'border-gold bg-gold/10' => $status === $key,
                   ])>
                    <div class="text-[10px] font-black uppercase tracking-widest text-[#A8B5C1]">{{ $label }}</div>
                    <div class="text-2xl font-black text-[#D6DEE6]">{{ $counts[$key] }}</div>
                </a>
            @endforeach
        </div>

        @if ($status)
            <a href="{{ route('vendor.orders.index') }}" class="text-xs font-bold text-gold hover:text-gold-hover">Clear filter</a>
        @endif

        <div class="bg-[#171717] border border-[#344453] rounded-2xl overflow-hidden">
            <table class="min-w-full divide-y divide-gray-800">
                <thead class="bg-[#263543]/50">
                    <tr>
                        <th class="px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-[#A8B5C1]">Order</th>
                        <th class="px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-[#A8B5C1]">Customer</th>
                        <th class="px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-[#A8B5C1]">Items</th>
                        <th class="px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-[#A8B5C1]">Total</th>
                        <th class="px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-[#A8B5C1]">Status</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @forelse ($orders as $order)
                        <tr class="hover:bg-[#263543]/30">
                            <td class="px-4 py-3">
                                <div class="font-mono font-bold text-[#D6DEE6] text-sm">{{ $order->order_number }}</div>
                                <div class="text-[10px] text-[#A8B5C1]">{{ $order->created_at->format('Y-m-d H:i') }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm text-[#D6DEE6]">{{ $order->user?->name ?? 'أ¢â‚¬â€‌' }}</td>
                            <td class="px-4 py-3 text-sm text-[#D6DEE6]">{{ $order->items->count() }}</td>
                            <td class="px-4 py-3 text-sm font-black text-gold">{{ number_format((float) $order->total, 2) }} <span class="text-[10px] text-[#A8B5C1]">LYD</span></td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-0.5 text-[10px] font-black uppercase tracking-wider rounded {{ $order->status->badgeClass() }}">{{ $order->status->label() }}</span>
                            </td>
                            <td class="px-4 py-3 text-left">
                                <a href="{{ route('vendor.orders.show', $order) }}" class="text-xs font-bold text-gold hover:text-gold-hover">View &rarr;</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-8 text-center text-sm text-[#A8B5C1]">No orders found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div>{{ $orders->links() }}</div>
    </div>
</x-layouts.dashboard>