<x-layouts.dashboard :title="'Reviews'">
    <div class="space-y-6">
        <div>
            <h1 class="text-3xl font-black text-white">Customer Reviews</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $counts['unreplied'] }} awaiting your reply</p>
        </div>

        @if (session('status'))
            <div class="rounded-xl border border-lime-400/30 bg-lime-400/10 p-3 text-sm text-lime-300">{{ session('status') }}</div>
        @endif

        <div class="space-y-3">
            @forelse ($reviews as $review)
                <div class="bg-[#171717] border border-gray-800 rounded-2xl p-5">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <div class="flex text-lime-400 text-sm">
                                    @for ($i = 1; $i <= 5; $i++)@if ($i <= $review->rating)<svg class="w-4 h-4 text-lime-400 inline-block" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>@else<svg class="w-4 h-4 text-gray-600 inline-block" fill="none" stroke="currentColor" viewBox="0 0 20 20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>@endif@endfor
                                </div>
                                <span class="text-sm font-bold text-white">{{ $review->user->name ?? '?' }}</span>
                                @if ($review->is_verified_purchase)
                                    <span class="text-[10px] font-black uppercase tracking-wider px-1.5 py-0.5 bg-lime-400/10 text-lime-400 border border-lime-400/30 rounded">Verified</span>
                                @endif
                            </div>
                            <a href="{{ route('shop.products.show', $review->product) }}" target="_blank" class="text-xs text-lime-400 hover:text-lime-300 mt-1 inline-block">{{ $review->product->name }}</a>
                            <div class="text-[10px] text-gray-500">{{ $review->created_at->format('Y-m-d H:i') }}</div>
                        </div>
                    </div>

                    @if ($review->title)<div class="font-bold text-white mt-2">{{ $review->title }}</div>@endif
                    <p class="text-sm text-gray-300 mt-1 whitespace-pre-line">{{ $review->body }}</p>

                    @if ($review->vendor_reply)
                        <div class="mt-3 p-3 rounded-lg bg-lime-400/5 border-l-2 border-lime-400">
                            <div class="text-[10px] font-black uppercase tracking-widest text-lime-400 mb-1">Your reply &middot; {{ $review->vendor_replied_at?->format('M d') }}</div>
                            <p class="text-sm text-gray-300">{{ $review->vendor_reply }}</p>
                            <form method="POST" action="{{ route('vendor.reviews.reply.delete', $review) }}" class="mt-2" onsubmit="return confirm('Remove reply?')">
                                @csrf @method('DELETE')
                                <button class="text-[10px] font-bold text-red-400 hover:text-red-300">Remove reply</button>
                            </form>
                        </div>
                    @else
                        <form method="POST" action="{{ route('vendor.reviews.reply', $review) }}" class="mt-3 flex gap-2">
                            @csrf
                            <input name="vendor_reply" type="text" required minlength="2" maxlength="1000" placeholder="Write your reply..." class="flex-1 rounded-lg bg-gray-900 border border-gray-800 text-white text-sm px-3 py-2 focus:border-lime-400">
                            <button type="submit" class="px-4 py-2 bg-lime-400 text-black font-black rounded-lg text-xs hover:bg-lime-300">Reply</button>
                        </form>
                    @endif
                </div>
            @empty
                <div class="bg-[#171717] border border-gray-800 rounded-2xl p-12 text-center text-gray-500">
                    No reviews yet
                </div>
            @endforelse
        </div>
        <div>{{ $reviews->links() }}</div>
    </div>
</x-layouts.dashboard>