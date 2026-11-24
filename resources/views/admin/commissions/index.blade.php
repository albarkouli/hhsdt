<x-layouts.dashboard :title="'Commission Rates'">
    <div class="space-y-6">
        <div>
            <h1 class="text-3xl font-black text-[#D6DEE6]">Commission Rates</h1>
            <p class="text-sm text-[#A8B5C1] mt-1">Control platform commission per vendor</p>
        </div>

        @if (session('status'))
            <div class="rounded-xl border border-gold/30 bg-gold/10 p-3 text-sm text-gold-hover">{{ session('status') }}</div>
        @endif
        @if ($errors->any())
            <div class="rounded-xl border border-red-500/30 bg-red-500/10 p-3 text-sm text-red-300">{{ $errors->first() }}</div>
        @endif

        {{-- Stats --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <div class="bg-[#171717] border border-[#344453] rounded-2xl p-5">
                <div class="text-[10px] font-black uppercase tracking-widest text-[#A8B5C1]">Average Rate</div>
                <div class="text-3xl font-black text-gold">{{ $stats['avg_rate'] }}%</div>
                <div class="text-xs text-[#A8B5C1] mt-1">Across {{ $stats['total_vendors'] }} vendors</div>
            </div>
            <div class="bg-[#171717] border border-[#344453] rounded-2xl p-5">
                <div class="text-[10px] font-black uppercase tracking-widest text-[#A8B5C1]">Range</div>
                <div class="text-3xl font-black text-[#D6DEE6]">{{ $stats['min_rate'] }}أ¢â‚¬â€œ{{ $stats['max_rate'] }}<span class="text-base text-[#A8B5C1]">%</span></div>
                <div class="text-xs text-[#A8B5C1] mt-1">Min أ¢â€ â€™ Max</div>
            </div>
            <div class="bg-[#171717] border border-[#344453] rounded-2xl p-5">
                <div class="text-[10px] font-black uppercase tracking-widest text-[#A8B5C1]">Total Earned</div>
                <div class="text-3xl font-black text-emerald-400">{{ number_format($stats['total_earned'], 2) }}</div>
                <div class="text-xs text-[#A8B5C1] mt-1">LYD all time</div>
            </div>
            <div class="bg-[#171717] border border-[#344453] rounded-2xl p-5">
                <div class="text-[10px] font-black uppercase tracking-widest text-[#A8B5C1]">Pending</div>
                <div class="text-3xl font-black text-yellow-400">{{ number_format($stats['pending_earned'], 2) }}</div>
                <div class="text-xs text-[#A8B5C1] mt-1">LYD awaiting settlement</div>
            </div>
        </div>

        {{-- Bulk update --}}
        <div class="bg-[#171717] border border-[#344453] rounded-2xl p-5">
            <h2 class="font-black text-[#D6DEE6] mb-3 flex items-center gap-2">
                <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5 19h14a2 2 0 001.84-2.75L13.74 4a2 2 0 00-3.5 0L3.34 16.25A2 2 0 005 19z"/></svg>
                Bulk update (all vendors)
            </h2>
            <form method="POST" action="{{ route('admin.commissions.bulk') }}" class="flex flex-wrap gap-3 items-end">
                @csrf @method('PUT')
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-[#A8B5C1] mb-1">New rate %</label>
                    <input name="bulk_rate" type="number" step="0.01" min="0" max="30" required placeholder="5.00"
                           class="w-32 rounded-lg bg-[#263543] border border-[#344453] text-[#D6DEE6] text-sm px-3 py-2.5 focus:border-gold">
                </div>
                <label class="inline-flex items-center gap-2 text-xs text-[#A8B5C1]">
                    <input type="checkbox" name="confirm" value="1" required class="w-4 h-4 rounded bg-[#263543] border-[#344453] text-gold focus:ring-gold">
                    Apply to ALL {{ $stats['total_vendors'] }} vendors
                </label>
                <button type="submit" onclick="return confirm('This will overwrite commission rate for ALL vendors. Continue?')"
                        class="px-5 py-2.5 bg-amber-500 hover:bg-amber-400 text-[#D6DEE6] font-black rounded-lg text-sm">
                    Apply to all
                </button>
            </form>
        </div>

        {{-- Vendors table --}}
        <div class="bg-[#171717] border border-[#344453] rounded-2xl overflow-hidden">
            <table class="min-w-full divide-y divide-gray-800">
                <thead class="bg-[#263543]/50">
                    <tr>
                        <th class="px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-[#A8B5C1]">Vendor</th>
                        <th class="px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-[#A8B5C1]">Status</th>
                        <th class="px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-[#A8B5C1]">Orders</th>
                        <th class="px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-[#A8B5C1]">Sales</th>
                        <th class="px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-[#A8B5C1]">Commission Rate</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @forelse ($vendors as $vendor)
                        <tr class="hover:bg-[#263543]/30">
                            <td class="px-4 py-3">
                                <div class="font-bold text-[#D6DEE6] text-sm">{{ $vendor->name }}</div>
                                <div class="text-[10px] text-[#A8B5C1]">/{{ $vendor->slug }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-0.5 text-[10px] font-black uppercase tracking-wider rounded {{ $vendor->status->value === 'active' ? 'bg-gold/10 text-gold border border-gold/30' : ($vendor->status->value === 'pending' ? 'bg-yellow-500/10 text-yellow-400 border border-yellow-500/30' : 'bg-red-500/10 text-red-400 border border-red-500/30') }}">{{ $vendor->status->label() }}</span>
                            </td>
                            <td class="px-4 py-3 text-sm text-[#D6DEE6]">{{ $vendor->orders_count }}</td>
                            <td class="px-4 py-3 text-sm font-bold text-[#D6DEE6]">{{ number_format((float) ($vendor->total_sales ?? 0), 2) }} <span class="text-[10px] text-[#A8B5C1]">LYD</span></td>
                            <td class="px-4 py-3">
                                <form method="POST" action="{{ route('admin.commissions.update', $vendor) }}" class="flex items-center gap-2">
                                    @csrf @method('PUT')
                                    <input name="commission_rate" type="number" step="0.01" min="0" max="30" value="{{ $vendor->commission_rate }}" required
                                           class="w-24 rounded-lg bg-[#263543] border border-[#344453] text-[#D6DEE6] text-sm font-black text-center px-2 py-1.5 focus:border-gold focus:ring-2 focus:ring-gold/20">
                                    <span class="text-[#A8B5C1] text-sm">%</span>
                                    <button type="submit" class="px-3 py-1.5 text-[10px] font-black uppercase bg-gold text-[#D6DEE6] rounded hover:bg-gold-hover">Save</button>
                                </form>
                            </td>
                            <td class="px-4 py-3 text-left text-xs text-[#A8B5C1]">
                                @if ((float) ($vendor->total_sales ?? 0) > 0)
                                    أ¢â€°ث† {{ number_format((float) $vendor->total_sales * ((float) $vendor->commission_rate / 100), 2) }} LYD earned
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-8 text-center text-sm text-[#A8B5C1]">No vendors yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="text-xs text-[#A8B5C1] text-center">
            Changing a rate applies only to <strong class="text-[#D6DEE6]">future orders</strong>. Past orders keep their original rate.
        </div>
    </div>
</x-layouts.dashboard>