<x-layouts.dashboard :title="'Notifications'">
    <div class="max-w-3xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#D6DEE6]">Notifications</h1>
                <p class="text-sm text-[#A8B5C1]">Recent activity on your account.</p>
            </div>
            @if (auth()->user()->unreadNotifications->count() > 0)
                <form method="POST" action="{{ route('notifications.mark-all-read') }}">
                    @csrf
                    <button class="text-sm text-indigo-600 hover:underline">Mark all as read</button>
                </form>
            @endif
        </div>

        @if (session('status'))
            <div class="rounded border border-green-300 bg-green-50 p-3 text-sm text-green-800">{{ session('status') }}</div>
        @endif

        @if ($notifications->isEmpty())
            <div class="bg-[#263543] rounded-lg shadow p-12 text-center">
                <p class="text-[#A8B5C1]">You have no notifications yet.</p>
            </div>
        @else
            <div class="bg-[#263543] rounded-lg shadow overflow-hidden divide-y divide-gray-100">
                @foreach ($notifications as $notification)
                    <div class="p-4 flex items-start gap-3 {{ $notification->read_at ? 'opacity-70' : 'bg-indigo-50/30' }}">
                        <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-lg shrink-0">
                            @switch($notification->data['type'] ?? '')
                                @case('order_placed') ًں“¦ @break
                                @case('order_status_changed') ًںڑڑ @break
                                @case('vendor_approved') âœ… @break
                                @case('vendor_suspended') âڑ ï¸ڈ @break
                                @default ًں””
                            @endswitch
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-medium text-[#D6DEE6]">{{ $notification->data['title'] ?? 'Notification' }}</div>
                            <div class="text-sm text-[#A8B5C1]">{{ $notification->data['message'] ?? '' }}</div>
                            <div class="text-xs text-[#A8B5C1] mt-1">{{ $notification->created_at->diffForHumans() }}</div>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            @if (isset($notification->data['url']))
                                <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                                    @csrf
                                    <button class="text-sm text-indigo-600 hover:underline">View</button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('notifications.destroy', $notification->id) }}">
                                @csrf
                                @method('DELETE')
                                <button class="text-sm text-[#A8B5C1] hover:text-red-600">أ—</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <div>{{ $notifications->links() }}</div>
        @endif
    </div>
</x-layouts.dashboard>