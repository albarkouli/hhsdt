<x-layouts.dashboard :title="'Message'">
    <div class="max-w-3xl mx-auto space-y-4">
        <a href="{{ route('admin.messages.index') }}" class="text-xs font-bold text-gray-500 hover:text-lime-400">&larr; All messages</a>

        @if (session('status'))
            <div class="rounded-xl border border-lime-400/30 bg-lime-400/10 p-3 text-sm text-lime-300">{{ session('status') }}</div>
        @endif

        <div class="bg-[#171717] border border-gray-800 rounded-2xl p-6">
            <div class="flex items-start justify-between mb-4 gap-3">
                <div>
                    <h1 class="text-2xl font-black text-white">{{ $message->subject ?: 'No subject' }}</h1>
                    <p class="text-sm text-gray-400 mt-1">From: <span class="font-bold text-white">{{ $message->name }}</span> &lt;<a href="mailto:{{ $message->email }}" class="text-lime-400 hover:text-lime-300">{{ $message->email }}</a>&gt;</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $message->created_at->format('Y-m-d H:i') }}</p>
                </div>
                <span class="px-2 py-1 text-[10px] font-black uppercase tracking-wider rounded
                    {{ $message->status === 'new' ? 'bg-lime-400/10 text-lime-400 border border-lime-400/30' : '' }}
                    {{ $message->status === 'read' ? 'bg-gray-700/50 text-gray-400 border border-gray-700' : '' }}
                    {{ $message->status === 'replied' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30' : '' }}">
                    {{ ucfirst($message->status) }}
                </span>
            </div>

            <div class="border-t border-gray-800 pt-4 mt-4">
                <p class="whitespace-pre-line text-gray-300 leading-relaxed">{{ $message->message }}</p>
            </div>
        </div>

        <div class="bg-[#171717] border border-gray-800 rounded-2xl p-4 flex flex-wrap gap-2">
            <a href="mailto:{{ $message->email }}?subject=Re: {{ urlencode($message->subject ?: 'Your message') }}"
               class="px-4 py-2 text-xs font-black uppercase bg-lime-400 text-black rounded-lg hover:bg-lime-300">Reply via email</a>

            @if ($message->status !== 'replied')
                <form method="POST" action="{{ route('admin.messages.replied', $message) }}">@csrf
                    <button class="px-4 py-2 text-xs font-black uppercase bg-emerald-500 text-white rounded-lg hover:bg-emerald-400">Mark replied</button>
                </form>
            @endif

            <form method="POST" action="{{ route('admin.messages.destroy', $message) }}" onsubmit="return confirm('Delete this message?')">@csrf @method('DELETE')
                <button class="px-4 py-2 text-xs font-black uppercase bg-red-500/20 text-red-400 border border-red-500/30 rounded-lg hover:bg-red-500/30">Delete</button>
            </form>
        </div>
    </div>
</x-layouts.dashboard>