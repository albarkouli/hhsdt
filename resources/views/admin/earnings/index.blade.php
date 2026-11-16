<x-layouts.dashboard :title="'Platform Earnings'">
    <div class="space-y-6">
        <div>
            <h1 class="text-3xl font-black text-white">Platform Earnings</h1>
            <p class="text-sm text-gray-500 mt-1">Commissions from delivered orders</p>
        </div>

        @php
            $totalEarned = \App\Models\PlatformEarning::sum('amount');
            $pending = \App\Models\PlatformEarning::where('status', 'pending')->sum('amount');
            $settled = \App\Models\PlatformEarning::where('status', 'settled')->sum('amount');
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-gradient-to-br from-lime-400 to-emerald-500 rounded-2xl p-5">
                <div class="text-[10px] font-black uppercase tracking-widest text-black/70 mb-2">Total Earned</div>
                <div class="text-3xl font-black text-black">{{ number_format((float) $totalEarned, 2) }}</div>
                <div class="text-xs text-black/70 font-bold mt-1">LYD</div>
            </div>
            <div class="bg-[#171717] border border-yellow-500/30 rounded-2xl p-5">
                <div class="text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2">Pending</div>
                <div class="text-3xl font-black text-yellow-400">{{ number_format((float) $pending, 2) }}</div>
                <div class="text-xs text-gray-500 font-bold mt-1">LYD</div>
            </div>
            <div class="bg-[#171717] border border-emerald-500/30 rounded-2xl p-5">
                <div class="text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2">Settled</div>
                <div class="text-3xl font-black text-emerald-400">{{ number_format((float) $settled, 2) }}</div>
                <div class="text-xs text-gray-500 font-bold mt-1">LYD</div>
            </div>
        </div>

        <div class="bg-[#171717] border border-gray-800 rounded-2xl overflow-hidden">
            <table class="min-w-full divide-y divide-gray-800">
                <thead class="bg-gray-900/50">
                    <tr>
                        <th class="px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-gray-500">Order</th>
                        <th class="px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-gray-500">Vendor</th>
                        <th class="px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-gray-500">Net</th>
                        <th class="px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-gray-500">Rate</th>
                        <th class="px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-gray-500">Commission</th>
                        <th class="px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-gray-500">Status</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @forelse ($earnings as $earning)
                        <tr class="hover:bg-gray-900/30">
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.orders.show', $earning->order) }}" class="font-mono font-bold text-lime-400 hover:text-lime-300 text-xs">{{ $earning->order->order_number }}</a>
                                <div class="text-[10px] text-gray-500">{{ $earning->created_at->format('Y-m-d') }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm text-white">{{ $earning->vendor->name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-300">{{ number_format((float) $earning->order_total, 2) }} LYD</td>
                            <td class="px-4 py-3 text-sm text-gray-400">{{ (float) $earning->commission_rate }}%</td>
                            <td class="px-4 py-3 text-sm font-black text-lime-400">{{ number_format((float) $earning->amount, 2) }} LYD</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-0.5 text-[10px] font-black uppercase tracking-wider rounded {{ $earning->isPending() ? 'bg-yellow-500/10 text-yellow-400 border border-yellow-500/30' : 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30' }}">{{ ucfirst($earning->status) }}</span>
                            </td>
                            <td class="px-4 py-3 text-left">
                                @if ($earning->isPending())
                                    <form method="POST" action="{{ route('admin.earnings.settle', $earning) }}">@csrf
                                        <button class="text-xs font-bold text-lime-400 hover:text-lime-300">Mark settled</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-4 py-8 text-center text-sm text-gray-500">No earnings yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div>{{ $earnings->links() }}</div>
    </div>
</x-layouts.dashboard>