<x-layouts.dashboard :title="'Orders'">
    <div class="space-y-6">
        <div>
            <h1 class="text-3xl font-black text-white">Orders</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $vendor->name }}</p>
        </div>

        @if (session('status'))
            <div class="rounded-xl border border-lime-400/30 bg-lime-400/10 p-3 text-sm text-lime-300">{{ session('status') }}</div>
        @endif
        @if (session('error'))
            <div class="rounded-xl border border-red-500/30 bg-red-500/10 p-3 text-sm text-red-300">{{ session('error') }}</div>
        @endif

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-2">
            @foreach (['pending' => 'Pending', 'pending_customer_approval' => 'Awaiting', 'confirmed' => 'Confirmed', 'shipped' => 'Shipped', 'delivered' => 'Delivered', 'cancelled' => 'Cancelled'] as $key => $label)
                <a href="{{ route('vendor.orders.index', ['status' => $key]) }}"
                   @class([
                       'rounded-xl border p-3 hover:border-lime-400/30 transition',
                       'border-gray-800 bg-[#171717]' => $status !== $key,
                       'border-lime-400 bg-lime-400/10' => $status === $key,
                   ])>
                    <div class="text-[10px] font-black uppercase tracking-widest text-gray-500">{{ $label }}</div>
                    <div class="text-2xl font-black text-white">{{ $counts[$key] }}</div>
                </a>
            @endforeach
        </div>

        @if ($status)
            <a href="{{ route('vendor.orders.index') }}" class="text-xs font-bold text-lime-400 hover:text-lime-300">Clear filter</a>
        @endif

        <div class="bg-[#171717] border border-gray-800 rounded-2xl overflow-hidden">
            <table class="min-w-full divide-y divide-gray-800">
                <thead class="bg-gray-900/50">
                    <tr>
                        <th class="px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-gray-500">Order</th>
                        <th class="px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-gray-500">Customer</th>
                        <th class="px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-gray-500">Items</th>
                        <th class="px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-gray-500">Total</th>
                        <th class="px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-gray-500">Status</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @forelse ($orders as $order)
                        <tr class="hover:bg-gray-900/30">
                            <td class="px-4 py-3">
                                <div class="font-mono font-bold text-white text-sm">{{ $order->order_number }}</div>
                                <div class="text-[10px] text-gray-500">{{ $order->created_at->format('Y-m-d H:i') }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-300">{{ $order->user?->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-300">{{ $order->items->count() }}</td>
                            <td class="px-4 py-3 text-sm font-black text-lime-400">{{ number_format((float) $order->total, 2) }} <span class="text-[10px] text-gray-500">LYD</span></td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-0.5 text-[10px] font-black uppercase tracking-wider rounded {{ $order->status->badgeClass() }}">{{ $order->status->label() }}</span>
                            </td>
                            <td class="px-4 py-3 text-left">
                                <a href="{{ route('vendor.orders.show', $order) }}" class="text-xs font-bold text-lime-400 hover:text-lime-300">View &rarr;</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-8 text-center text-sm text-gray-500">No orders found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div>{{ $orders->links() }}</div>
    </div>
</x-layouts.dashboard>