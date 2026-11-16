<x-layouts.dashboard :title="'Vendors'">
    <div class="space-y-6">
        <div>
            <h1 class="text-3xl font-black text-white">Vendors</h1>
            <p class="text-sm text-gray-500 mt-1">Review and manage stores</p>
        </div>

        @if (session('status'))
            <div class="rounded-xl border border-lime-400/30 bg-lime-400/10 p-3 text-sm text-lime-300">{{ session('status') }}</div>
        @endif

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            @foreach (['pending' => 'Pending', 'active' => 'Active', 'suspended' => 'Suspended', 'rejected' => 'Rejected'] as $key => $label)
                <a href="{{ route('admin.vendors.index', ['status' => $key]) }}"
                   @class([
                       'rounded-xl border p-4 hover:border-lime-400/30 transition',
                       'border-gray-800 bg-[#171717]' => $status !== $key,
                       'border-lime-400 bg-lime-400/10' => $status === $key,
                   ])>
                    <div class="text-[10px] font-black uppercase tracking-widest text-gray-500">{{ $label }}</div>
                    <div class="text-2xl font-black text-white">{{ $counts[$key] }}</div>
                </a>
            @endforeach
        </div>

        @if ($status)
            <a href="{{ route('admin.vendors.index') }}" class="text-xs font-bold text-lime-400 hover:text-lime-300">Clear filter</a>
        @endif

        <div class="bg-[#171717] border border-gray-800 rounded-2xl overflow-hidden">
            <table class="min-w-full divide-y divide-gray-800">
                <thead class="bg-gray-900/50">
                    <tr>
                        <th class="px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-gray-500">Store</th>
                        <th class="px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-gray-500">Owner</th>
                        <th class="px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-gray-500">City</th>
                        <th class="px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-gray-500">Status</th>
                        <th class="px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @forelse ($vendors as $vendor)
                        <tr class="hover:bg-gray-900/30">
                            <td class="px-4 py-3">
                                <div class="font-bold text-white text-sm">{{ $vendor->name }}</div>
                                <div class="text-[10px] text-gray-500">/{{ $vendor->slug }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-300">
                                {{ $vendor->owner?->name ?? '—' }}
                                <div class="text-[10px] text-gray-500">{{ $vendor->owner?->email }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-400">{{ $vendor->city ?? '—' }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $badge = [
                                        'pending'   => 'bg-yellow-500/10 text-yellow-400 border-yellow-500/30',
                                        'active'    => 'bg-lime-400/10 text-lime-400 border-lime-400/30',
                                        'suspended' => 'bg-red-500/10 text-red-400 border-red-500/30',
                                        'rejected'  => 'bg-gray-700/50 text-gray-400 border-gray-700',
                                    ][$vendor->status->value] ?? 'bg-gray-700 text-gray-400 border-gray-700';
                                @endphp
                                <span class="px-2 py-0.5 text-[10px] font-black uppercase tracking-wider rounded border {{ $badge }}">{{ $vendor->status->label() }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-1">
                                    @if ($vendor->status->value === 'pending')
                                        <form method="POST" action="{{ route('admin.vendors.approve', $vendor) }}">@csrf
                                            <button class="px-2 py-1 text-[10px] font-black uppercase bg-lime-400 text-black rounded hover:bg-lime-300">Approve</button>
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
                                            <button class="px-2 py-1 text-[10px] font-black uppercase bg-indigo-500 text-white rounded hover:bg-indigo-400">Reactivate</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-8 text-center text-sm text-gray-500">No vendors found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div>{{ $vendors->links() }}</div>
    </div>
</x-layouts.dashboard>