<x-layouts.dashboard :title="'Messages'">
    <div class="space-y-6">
        <div>
            <h1 class="text-3xl font-black text-white">Contact Messages</h1>
            <p class="text-sm text-gray-500 mt-1">From customers and visitors</p>
        </div>

        @if (session('status'))
            <div class="rounded-xl border border-lime-400/30 bg-lime-400/10 p-3 text-sm text-lime-300">{{ session('status') }}</div>
        @endif

        <div class="grid grid-cols-3 gap-3">
            @foreach (['new' => 'New', 'read' => 'Read', 'replied' => 'Replied'] as $key => $label)
                <a href="{{ route('admin.messages.index', ['status' => $key]) }}"
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
            <a href="{{ route('admin.messages.index') }}" class="text-xs font-bold text-lime-400 hover:text-lime-300">Clear filter</a>
        @endif

        <div class="bg-[#171717] border border-gray-800 rounded-2xl overflow-hidden">
            <table class="min-w-full divide-y divide-gray-800">
                <thead class="bg-gray-900/50">
                    <tr>
                        <th class="px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-gray-500">From</th>
                        <th class="px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-gray-500">Subject</th>
                        <th class="px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-gray-500">Status</th>
                        <th class="px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-gray-500">Date</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @forelse ($messages as $message)
                        <tr class="{{ $message->status === 'new' ? 'bg-lime-400/5' : '' }} hover:bg-gray-900/30">
                            <td class="px-4 py-3">
                                <div class="font-bold text-white text-sm">{{ $message->name }}</div>
                                <div class="text-[10px] text-gray-500">{{ $message->email }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-300">{{ Str::limit($message->subject ?: $message->message, 60) }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $badge = [
                                        'new'     => 'bg-lime-400/10 text-lime-400 border-lime-400/30',
                                        'read'    => 'bg-gray-700/50 text-gray-400 border-gray-700',
                                        'replied' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30',
                                    ][$message->status] ?? 'bg-gray-700 text-gray-400 border-gray-700';
                                @endphp
                                <span class="px-2 py-0.5 text-[10px] font-black uppercase tracking-wider rounded border {{ $badge }}">{{ ucfirst($message->status) }}</span>
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-500">{{ $message->created_at->format('M d, H:i') }}</td>
                            <td class="px-4 py-3 text-left">
                                <a href="{{ route('admin.messages.show', $message) }}" class="text-xs font-bold text-lime-400 hover:text-lime-300">View &rarr;</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-8 text-center text-sm text-gray-500">No messages yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div>{{ $messages->links() }}</div>
    </div>
</x-layouts.dashboard>