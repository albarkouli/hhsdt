<x-layouts.dashboard :title="'Messages'">
    <div class="space-y-6">
        <div>
            <h1 class="text-3xl font-black text-[#D6DEE6]">Contact Messages</h1>
            <p class="text-sm text-[#A8B5C1] mt-1">From customers and visitors</p>
        </div>

        @if (session('status'))
            <div class="rounded-xl border border-gold/30 bg-gold/10 p-3 text-sm text-gold-hover">{{ session('status') }}</div>
        @endif

        <div class="grid grid-cols-3 gap-3">
            @foreach (['new' => 'New', 'read' => 'Read', 'replied' => 'Replied'] as $key => $label)
                <a href="{{ route('admin.messages.index', ['status' => $key]) }}"
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
            <a href="{{ route('admin.messages.index') }}" class="text-xs font-bold text-gold hover:text-gold-hover">Clear filter</a>
        @endif

        <div class="bg-[#171717] border border-[#344453] rounded-2xl overflow-hidden">
            <table class="min-w-full divide-y divide-gray-800">
                <thead class="bg-[#263543]/50">
                    <tr>
                        <th class="px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-[#A8B5C1]">From</th>
                        <th class="px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-[#A8B5C1]">Subject</th>
                        <th class="px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-[#A8B5C1]">Status</th>
                        <th class="px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-[#A8B5C1]">Date</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @forelse ($messages as $message)
                        <tr class="{{ $message->status === 'new' ? 'bg-gold/5' : '' }} hover:bg-[#263543]/30">
                            <td class="px-4 py-3">
                                <div class="font-bold text-[#D6DEE6] text-sm">{{ $message->name }}</div>
                                <div class="text-[10px] text-[#A8B5C1]">{{ $message->email }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm text-[#D6DEE6]">{{ Str::limit($message->subject ?: $message->message, 60) }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $badge = [
                                        'new'     => 'bg-gold/10 text-gold border-gold/30',
                                        'read'    => 'bg-[#2D3D4C]/50 text-[#A8B5C1] border-[#344453]',
                                        'replied' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30',
                                    ][$message->status] ?? 'bg-[#2D3D4C] text-[#A8B5C1] border-[#344453]';
                                @endphp
                                <span class="px-2 py-0.5 text-[10px] font-black uppercase tracking-wider rounded border {{ $badge }}">{{ ucfirst($message->status) }}</span>
                            </td>
                            <td class="px-4 py-3 text-xs text-[#A8B5C1]">{{ $message->created_at->format('M d, H:i') }}</td>
                            <td class="px-4 py-3 text-left">
                                <a href="{{ route('admin.messages.show', $message) }}" class="text-xs font-bold text-gold hover:text-gold-hover">View &rarr;</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-8 text-center text-sm text-[#A8B5C1]">No messages yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div>{{ $messages->links() }}</div>
    </div>
</x-layouts.dashboard>