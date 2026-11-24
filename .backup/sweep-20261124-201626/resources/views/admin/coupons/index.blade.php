<x-layouts.dashboard :title="'Coupons'">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-black text-white">Coupons</h1>
                <p class="text-sm text-gray-500 mt-1">Discount codes for customers</p>
            </div>
            <a href="{{ route('admin.coupons.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-lime-400 hover:bg-lime-300 text-black font-black rounded-lg text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                New coupon
            </a>
        </div>

        @if (session('status'))
            <div class="rounded-xl border border-lime-400/30 bg-lime-400/10 p-3 text-sm text-lime-300">{{ session('status') }}</div>
        @endif

        <div class="flex gap-2 flex-wrap">
            <a href="{{ route('admin.coupons.index') }}" class="px-3 py-1.5 text-xs font-black uppercase tracking-wider rounded-lg {{ ! $filter ? 'bg-lime-400 text-black' : 'bg-[#171717] text-gray-400 border border-gray-800 hover:border-lime-400/30' }}">All</a>
            <a href="{{ route('admin.coupons.index', ['filter' => 'active']) }}" class="px-3 py-1.5 text-xs font-black uppercase tracking-wider rounded-lg {{ $filter === 'active' ? 'bg-lime-400 text-black' : 'bg-[#171717] text-gray-400 border border-gray-800 hover:border-lime-400/30' }}">Active</a>
            <a href="{{ route('admin.coupons.index', ['filter' => 'expired']) }}" class="px-3 py-1.5 text-xs font-black uppercase tracking-wider rounded-lg {{ $filter === 'expired' ? 'bg-lime-400 text-black' : 'bg-[#171717] text-gray-400 border border-gray-800 hover:border-lime-400/30' }}">Expired</a>
            <a href="{{ route('admin.coupons.index', ['filter' => 'inactive']) }}" class="px-3 py-1.5 text-xs font-black uppercase tracking-wider rounded-lg {{ $filter === 'inactive' ? 'bg-lime-400 text-black' : 'bg-[#171717] text-gray-400 border border-gray-800 hover:border-lime-400/30' }}">Inactive</a>
        </div>

        <div class="bg-[#171717] border border-gray-800 rounded-2xl overflow-hidden">
            <table class="min-w-full divide-y divide-gray-800">
                <thead class="bg-gray-900/50">
                    <tr>
                        <th class="px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-gray-500">Code</th>
                        <th class="px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-gray-500">Discount</th>
                        <th class="px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-gray-500">Uses</th>
                        <th class="px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-gray-500">Expires</th>
                        <th class="px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-gray-500">Status</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @forelse ($coupons as $coupon)
                        <tr class="hover:bg-gray-900/30">
                            <td class="px-4 py-3">
                                <div class="font-mono font-black text-white text-sm">{{ $coupon->code }}</div>
                                <div class="text-[10px] text-gray-500">{{ $coupon->name }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-300">
                                @if ($coupon->isPercent())
                                    <span class="font-black text-lime-400">{{ (float) $coupon->value }}%</span>
                                @else
                                    <span class="font-black text-lime-400">{{ number_format((float) $coupon->value, 2) }} LYD</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-400">
                                {{ $coupon->used_count }}@if ($coupon->max_uses) / {{ $coupon->max_uses }}@else / ∞ @endif
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-500">{{ $coupon->expires_at?->format('Y-m-d') ?? 'Never' }}</td>
                            <td class="px-4 py-3">
                                @if ($coupon->isExpired())
                                    <span class="px-2 py-0.5 text-[10px] font-black uppercase tracking-wider rounded bg-gray-700/50 text-gray-400 border border-gray-700">Expired</span>
                                @elseif (! $coupon->is_active)
                                    <span class="px-2 py-0.5 text-[10px] font-black uppercase tracking-wider rounded bg-red-500/10 text-red-400 border border-red-500/30">Inactive</span>
                                @elseif ($coupon->isExhausted())
                                    <span class="px-2 py-0.5 text-[10px] font-black uppercase tracking-wider rounded bg-orange-500/10 text-orange-400 border border-orange-500/30">Used up</span>
                                @else
                                    <span class="px-2 py-0.5 text-[10px] font-black uppercase tracking-wider rounded bg-lime-400/10 text-lime-400 border border-lime-400/30">Active</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex gap-1 justify-end">
                                    <form method="POST" action="{{ route('admin.coupons.toggle', $coupon) }}">@csrf
                                        <button class="px-2 py-1 text-[10px] font-black uppercase bg-yellow-500/20 text-yellow-400 border border-yellow-500/30 rounded hover:bg-yellow-500/30">{{ $coupon->is_active ? 'Off' : 'On' }}</button>
                                    </form>
                                    <a href="{{ route('admin.coupons.edit', $coupon) }}" class="px-2 py-1 text-[10px] font-black uppercase bg-indigo-500 text-white rounded hover:bg-indigo-400">Edit</a>
                                    <form method="POST" action="{{ route('admin.coupons.destroy', $coupon) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')
                                        <button class="px-2 py-1 text-[10px] font-black uppercase bg-red-500/20 text-red-400 border border-red-500/30 rounded hover:bg-red-500/30">Del</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-8 text-center text-sm text-gray-500">No coupons yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div>{{ $coupons->links() }}</div>
    </div>
</x-layouts.dashboard>