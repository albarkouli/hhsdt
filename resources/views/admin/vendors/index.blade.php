<x-layouts.dashboard :title="'Vendors'">
    <div class="space-y-6">
        <div>
            <h1 class="text-3xl font-black text-[#D6DEE6]">Vendors</h1>
            <p class="text-sm text-[#A8B5C1] mt-1">Review and manage stores</p>
        </div>

        @if (session('status'))
            <div class="rounded-xl border border-gold/30 bg-gold/10 p-3 text-sm text-gold-hover">{{ session('status') }}</div>
        @endif

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            @foreach (['pending' => 'Pending', 'active' => 'Active', 'suspended' => 'Suspended', 'rejected' => 'Rejected'] as $key => $label)
                <a href="{{ route('admin.vendors.index', ['status' => $key]) }}"
                   @class([
                       'rounded-xl border p-4 hover:border-gold/30 transition',
                       'border-[#344453] bg-[#171717]' => $status !== $key,
                       'border-gold bg-gold/10' => $status === $key,
                   ])>
                    <div class="text-[10px] font-black uppercase tracking-widest text-[#A8B5C1]">{{ $label }}</div>
                    <div class="text-2xl font-black text-[#D6DEE6]">{{ $counts[$key] }}</div>
                </a>
            @endforeach
        </div>

        @if ($status)
            <a href="{{ route('admin.vendors.index') }}" class="text-xs font-bold text-gold hover:text-gold-hover">Clear filter</a>
        @endif

        <div class="bg-[#171717] border border-[#344453] rounded-2xl overflow-hidden">
            <table class="min-w-full divide-y divide-gray-800">
                <thead class="bg-[#263543]/50">
                    <tr>
                        <th class="px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-[#A8B5C1]">Store</th>
                        <th class="px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-[#A8B5C1]">Owner</th>
                        <th class="px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-[#A8B5C1]">City</th>
                        <th class="px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-[#A8B5C1]">Status</th>
                        <th class="px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-[#A8B5C1]">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @forelse ($vendors as $vendor)
                        <tr class="hover:bg-[#263543]/30">
                            <td class="px-4 py-3">
                                <div class="font-bold text-[#D6DEE6] text-sm">{{ $vendor->name }}</div>
                                <div class="text-[10px] text-[#A8B5C1]">/{{ $vendor->slug }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm text-[#D6DEE6]">
                                {{ $vendor->owner?->name ?? 'أ¢â‚¬â€‌' }}
                                <div class="text-[10px] text-[#A8B5C1]">{{ $vendor->owner?->email }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm text-[#A8B5C1]">{{ $vendor->city ?? 'أ¢â‚¬â€‌' }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $badge = [
                                        'pending'   => 'bg-yellow-500/10 text-yellow-400 border-yellow-500/30',
                                        'active'    => 'bg-gold/10 text-gold border-gold/30',
                                        'suspended' => 'bg-red-500/10 text-red-400 border-red-500/30',
                                        'rejected'  => 'bg-[#2D3D4C]/50 text-[#A8B5C1] border-[#344453]',
                                    ][$vendor->status->value] ?? 'bg-[#2D3D4C] text-[#A8B5C1] border-[#344453]';
                                @endphp
                                <span class="px-2 py-0.5 text-[10px] font-black uppercase tracking-wider rounded border {{ $badge }}">{{ $vendor->status->label() }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-1">
                                    @if ($vendor->status->value === 'pending')
                                        <form method="POST" action="{{ route('admin.vendors.approve', $vendor) }}">@csrf
                                            <button class="px-2 py-1 text-[10px] font-black uppercase bg-gold text-[#D6DEE6] rounded hover:bg-gold-hover">Approve</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.vendors.reject', $vendor) }}">@csrf
                                            <button class="px-2 py-1 text-[10px] font-black uppercase bg-red-500/20 text-red-400 border border-red-500/30 rounded hover:bg-red-500/30">Reject</button>
                                        </form>
                                    @elseif ($vendor->status->value === 'active')
                                        <form method="POST" action="{{ route('admin.vendors.suspend', $vendor) }}">@csrf
                                            <button class="px-2 py-1 text-[10px] font-black uppercase bg-red-500/20 text-red-400 border border-red-500/30 rounded hover:bg-red-500/30">Suspend</button>
                                        </form>
                                    @elseif (in_array($vendor->status->value, ['suspended','rejected'], true))
                                        <form method="POST" action="{{ route('admin.vendors.reactivate', $vendor) }}">@csrf
                                            <button class="px-2 py-1 text-[10px] font-black uppercase bg-indigo-500 text-[#D6DEE6] rounded hover:bg-indigo-400">Reactivate</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-8 text-center text-sm text-[#A8B5C1]">No vendors found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div>{{ $vendors->links() }}</div>
    </div>
</x-layouts.dashboard>