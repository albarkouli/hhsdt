<x-layouts.shop :title="'Review order changes'">
    <div class="max-w-3xl mx-auto space-y-6">
        <div>
            <a href="{{ route('shop.orders.show', $order) }}" class="text-sm text-[#A8B5C1] hover:underline">â†گ Back to order</a>
            <h1 class="text-2xl font-bold text-[#D6DEE6] mt-1">Vendor proposed a change</h1>
            <p class="text-sm text-[#A8B5C1]">Order {{ $order->order_number }} from {{ $order->vendor->name }}</p>
        </div>

        @if (session('cart_error'))
            <div class="rounded border border-red-300 bg-red-50 p-3 text-sm text-red-800">{{ session('cart_error') }}</div>
        @endif

        <div class="rounded border border-purple-300 bg-purple-50 p-4 text-sm text-purple-900">
            <strong>Message from {{ $order->vendor->name }}:</strong>
            <p class="mt-2 whitespace-pre-line">{{ $modification->note }}</p>
        </div>

        {{-- Comparison table --}}
        <div class="bg-[#263543] rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#263543]">
                    <tr>
                        <th class="px-4 py-3 text-right text-xs font-medium text-[#A8B5C1] uppercase">Product</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-[#A8B5C1] uppercase">Original</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-[#A8B5C1] uppercase">Proposed</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-[#A8B5C1] uppercase">New subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($modification->proposed_items as $proposed)
                        @php
                            $original = collect($modification->original_items)->firstWhere('id', $proposed['id']);
                        @endphp
                        <tr>
                            <td class="px-4 py-3 text-sm text-[#D6DEE6]">{{ $proposed['name'] }}</td>
                            <td class="px-4 py-3 text-center text-sm text-[#A8B5C1] line-through">
                                {{ $original['quantity'] }}
                            </td>
                            <td class="px-4 py-3 text-center text-sm font-medium text-indigo-600">
                                {{ $proposed['quantity'] }}
                            </td>
                            <td class="px-4 py-3 text-sm text-right font-medium text-[#D6DEE6]">
                                {{ number_format($proposed['subtotal'], 2) }} LYD
                            </td>
                        </tr>
                    @endforeach

                    {{-- Removed items --}}
                    @foreach ($modification->original_items as $original)
                        @if (! collect($modification->proposed_items)->firstWhere('id', $original['id']))
                            <tr class="bg-red-50/40">
                                <td class="px-4 py-3 text-sm text-[#D6DEE6]">{{ $original['name'] }}</td>
                                <td class="px-4 py-3 text-center text-sm text-[#A8B5C1] line-through">
                                    {{ $original['quantity'] }}
                                </td>
                                <td class="px-4 py-3 text-center text-sm font-medium text-red-600">Removed</td>
                                <td class="px-4 py-3 text-sm text-right text-[#A8B5C1]">0.00 LYD</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
                <tfoot class="bg-[#263543]">
                    <tr>
                        <td colspan="3" class="px-4 py-3 text-sm text-[#A8B5C1] text-right">Original total:</td>
                        <td class="px-4 py-3 text-sm text-right text-[#A8B5C1] line-through">
                            {{ number_format((float) $modification->original_total, 2) }} LYD
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="px-4 py-3 font-bold text-[#D6DEE6] text-right">New total:</td>
                        <td class="px-4 py-3 font-bold text-indigo-600 text-right">
                            {{ number_format((float) $modification->proposed_total, 2) }} LYD
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- Actions --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <form method="POST" action="{{ route('shop.orders.accept', $order) }}"
                  onsubmit="return confirm('Accept the new quantities and continue with this order?')">
                @csrf
                <button class="w-full px-4 py-3 bg-green-600 hover:bg-green-700 text-[#D6DEE6] font-semibold rounded-lg">
                    âœ“ Accept changes
                </button>
            </form>
            <form method="POST" action="{{ route('shop.orders.reject', $order) }}"
                  onsubmit="return confirm('Reject the changes? This will cancel your order.')">
                @csrf
                <button class="w-full px-4 py-3 bg-red-600 hover:bg-red-700 text-[#D6DEE6] font-semibold rounded-lg">
                    âœ— Reject and cancel order
                </button>
            </form>
        </div>

        <p class="text-xs text-center text-[#A8B5C1]">
            If you do nothing, the order stays on hold. You can review it later from <a href="{{ route('shop.orders.index') }}" class="underline">My Orders</a>.
        </p>
    </div>
</x-layouts.shop>