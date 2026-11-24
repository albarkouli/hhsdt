<x-layouts.dashboard :title="'Message'">
    <div class="max-w-3xl mx-auto space-y-4">
        <a href="{{ route('admin.messages.index') }}" class="text-xs font-bold text-[#A8B5C1] hover:text-gold">&larr; All messages</a>

        @if (session('status'))
            <div class="rounded-xl border border-gold/30 bg-gold/10 p-3 text-sm text-gold-hover">{{ session('status') }}</div>
        @endif

        <div class="bg-[#171717] border border-[#344453] rounded-2xl p-6">
            <div class="flex items-start justify-between mb-4 gap-3">
                <div>
                    <h1 class="text-2xl font-black text-[#D6DEE6]">{{ $message->subject ?: 'No subject' }}</h1>
                    <p class="text-sm text-[#A8B5C1] mt-1">From: <span class="font-bold text-[#D6DEE6]">{{ $message->name }}</span> &lt;<a href="mailto:{{ $message->email }}" class="text-gold hover:text-gold-hover">{{ $message->email }}</a>&gt;</p>
                    <p class="text-xs text-[#A8B5C1] mt-1">{{ $message->created_at->format('Y-m-d H:i') }}</p>
                </div>
                <span class="px-2 py-1 text-[10px] font-black uppercase tracking-wider rounded
                    {{ $message->status === 'new' ? 'bg-gold/10 text-gold border border-gold/30' : '' }}
                    {{ $message->status === 'read' ? 'bg-[#2D3D4C]/50 text-[#A8B5C1] border border-[#344453]' : '' }}
                    {{ $message->status === 'replied' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30' : '' }}">
                    {{ ucfirst($message->status) }}
                </span>
            </div>

            <div class="border-t border-[#344453] pt-4 mt-4">
                <p class="whitespace-pre-line text-[#D6DEE6] leading-relaxed">{{ $message->message }}</p>
            </div>
        </div>

        <div class="bg-[#171717] border border-[#344453] rounded-2xl p-4 flex flex-wrap gap-2">
            <a href="mailto:{{ $message->email }}?subject=Re: {{ urlencode($message->subject ?: 'Your message') }}"
               class="px-4 py-2 text-xs font-black uppercase bg-gold text-[#D6DEE6] rounded-lg hover:bg-gold-hover">Reply via email</a>

            @if ($message->status !== 'replied')
                <form method="POST" action="{{ route('admin.messages.replied', $message) }}">@csrf
                    <button class="px-4 py-2 text-xs font-black uppercase bg-emerald-500 text-[#D6DEE6] rounded-lg hover:bg-emerald-400">Mark replied</button>
                </form>
            @endif

            <form method="POST" action="{{ route('admin.messages.destroy', $message) }}" onsubmit="return confirm('Delete this message?')">@csrf @method('DELETE')
                <button class="px-4 py-2 text-xs font-black uppercase bg-red-500/20 text-red-400 border border-red-500/30 rounded-lg hover:bg-red-500/30">Delete</button>
            </form>
        </div>
    </div>
</x-layouts.dashboard>