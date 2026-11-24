<x-layouts.dashboard :title="'Reviews'">
    <div class="space-y-6">
        <div>
            <h1 class="text-3xl font-black text-[#D6DEE6]">Product Reviews</h1>
            <p class="text-sm text-[#A8B5C1] mt-1">Moderate customer reviews</p>
        </div>

        @if (session('status'))
            <div class="rounded-xl border border-gold/30 bg-gold/10 p-3 text-sm text-gold-hover">{{ session('status') }}</div>
        @endif

        <div class="grid grid-cols-3 gap-3">
            @foreach (['all' => 'All', 'published' => 'Published', 'hidden' => 'Hidden'] as $key => $label)
                <a href="{{ route('admin.reviews.index', ['filter' => $key === 'all' ? null : $key]) }}"
                   @class([
                       'rounded-xl border p-3 hover:border-gold/30 transition',
                       'border-[#344453] bg-[#171717]' => $filter !== ($key === 'all' ? null : $key),
                       'border-gold bg-gold/10' => $filter === ($key === 'all' ? null : $key),
                   ])>
                    <div class="text-[10px] font-black uppercase tracking-widest text-[#A8B5C1]">{{ $label }}</div>
                    <div class="text-2xl font-black text-[#D6DEE6]">{{ $counts[$key] }}</div>
                </a>
            @endforeach
        </div>

        <div class="space-y-3">
            @forelse ($reviews as $review)
                <div class="bg-[#171717] border {{ $review->is_published ? 'border-[#344453]' : 'border-red-500/30 opacity-70' }} rounded-2xl p-5">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap mb-1">
                                <div class="flex text-gold text-sm">
                                    @for ($i = 1; $i <= 5; $i++)@if ($i <= $review->rating)<svg class="w-4 h-4 text-gold inline-block" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>@else<svg class="w-4 h-4 text-[#A8B5C1] inline-block" fill="none" stroke="currentColor" viewBox="0 0 20 20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>@endif@endfor
                                </div>
                                <span class="text-sm font-bold text-[#D6DEE6]">{{ $review->user->name ?? '?' }}</span>
                                @if ($review->is_verified_purchase)
                                    <span class="text-[10px] font-black uppercase tracking-wider px-1.5 py-0.5 bg-gold/10 text-gold border border-gold/30 rounded">Verified</span>
                                @endif
                                @unless ($review->is_published)
                                    <span class="text-[10px] font-black uppercase tracking-wider px-1.5 py-0.5 bg-red-500/10 text-red-400 border border-red-500/30 rounded">Hidden</span>
                                @endunless
                            </div>
                            <div class="text-xs text-[#A8B5C1] mb-2">
                                On: <a href="{{ route('shop.products.show', $review->product) }}" target="_blank" class="text-gold hover:text-gold-hover">{{ $review->product->name }}</a>
                                &middot; {{ $review->created_at->format('Y-m-d H:i') }}
                            </div>
                            @if ($review->title)
                                <div class="font-bold text-[#D6DEE6] mt-2">{{ $review->title }}</div>
                            @endif
                            <p class="text-sm text-[#D6DEE6] mt-1 whitespace-pre-line">{{ $review->body }}</p>
                        </div>
                        <div class="flex flex-col gap-1 shrink-0">
                            <form method="POST" action="{{ route('admin.reviews.toggle', $review) }}">@csrf
                                <button class="w-full px-2 py-1 text-[10px] font-black uppercase rounded {{ $review->is_published ? 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30 hover:bg-yellow-500/30' : 'bg-gold text-[#D6DEE6] hover:bg-gold-hover' }}">
                                    {{ $review->is_published ? 'Hide' : 'Publish' }}
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')
                                <button class="w-full px-2 py-1 text-[10px] font-black uppercase bg-red-500/20 text-red-400 border border-red-500/30 rounded hover:bg-red-500/30">Del</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-[#171717] border border-[#344453] rounded-2xl p-8 text-center text-sm text-[#A8B5C1]">No reviews found</div>
            @endforelse
        </div>
        <div>{{ $reviews->links() }}</div>
    </div>
</x-layouts.dashboard>