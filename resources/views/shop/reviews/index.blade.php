<x-layouts.shop :title="'Reviews - ' . $product->name">
    <nav class="text-xs font-semibold text-[#A8B5C1] mb-4 flex items-center flex-wrap gap-1">
        <a href="{{ route('shop.products.index') }}" class="hover:text-gold">Shop</a>
        <span class="text-[#A8B5C1]">/</span>
        <a href="{{ route('shop.products.show', $product) }}" class="hover:text-gold">{{ Str::limit($product->name, 40) }}</a>
        <span class="text-[#A8B5C1]">/</span>
        <span class="text-[#D6DEE6]">Reviews</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-5">
            {{-- HEADER --}}
            <div class="bg-[#171717] border border-[#344453] rounded-2xl p-6">
                <h1 class="text-2xl font-black text-[#D6DEE6]">{{ $product->name }}</h1>
                <div class="flex items-center gap-3 mt-3">
                    <div class="flex gap-0.5">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= round($product->avg_rating))
                                <svg class="w-5 h-5 text-gold" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @else
                                <svg class="w-5 h-5 text-[#A8B5C1]" fill="none" stroke="currentColor" viewBox="0 0 20 20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                            @endif
                        @endfor
                    </div>
                    <span class="text-sm font-bold text-[#D6DEE6]">
                        @if ($product->reviews_count > 0)
                            {{ number_format($product->avg_rating, 1) }} / 5 &middot; {{ $product->reviews_count }} review(s)
                        @else
                            No reviews yet
                        @endif
                    </span>
                </div>
            </div>

            {{-- MY REVIEW / FORM --}}
            @auth
                @if ($myReview)
                    <div class="bg-gold/5 border border-gold/30 rounded-2xl p-5">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex-1 min-w-0">
                                <div class="text-[10px] font-black uppercase tracking-widest text-gold mb-1">Your review</div>
                                <div class="flex gap-0.5 mb-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $myReview->rating)
                                            <svg class="w-4 h-4 text-gold" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        @endif
                                    @endfor
                                </div>
                                @if ($myReview->title)<div class="font-bold text-[#D6DEE6]">{{ $myReview->title }}</div>@endif
                                <p class="text-sm text-[#D6DEE6] mt-1">{{ $myReview->body }}</p>
                            </div>
                            <form method="POST" action="{{ route('shop.reviews.destroy', [$product, $myReview]) }}" onsubmit="return confirm('Delete your review?')">
                                @csrf @method('DELETE')
                                <button class="text-xs font-bold text-red-400 hover:text-red-300">Delete</button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="bg-[#171717] border border-[#344453] rounded-2xl p-5">
                        <h2 class="font-black text-[#D6DEE6] mb-4">Write a review</h2>
                        @if (session('cart_error'))
                            <div class="mb-3 rounded-lg border border-red-500/30 bg-red-500/10 p-2 text-sm text-red-300">{{ session('cart_error') }}</div>
                        @endif
                        <form method="POST" action="{{ route('shop.reviews.store', $product) }}" class="space-y-3">
                            @csrf
                            <div>
                                <label class="block text-xs font-black uppercase tracking-widest text-[#A8B5C1] mb-1.5">Rating *</label>
                                <select name="rating" required class="w-full rounded-lg bg-[#263543] border border-[#344453] text-[#D6DEE6] text-sm px-3 py-2.5 focus:border-gold">
                                    <option value="">Select rating</option>
                                    @for ($i = 5; $i >= 1; $i--)
                                        <option value="{{ $i }}" @selected(old('rating') == $i)>{{ $i }} star{{ $i > 1 ? 's' : '' }}</option>
                                    @endfor
                                </select>
                                @error('rating')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-xs font-black uppercase tracking-widest text-[#A8B5C1] mb-1.5">Title (optional)</label>
                                <input name="title" type="text" maxlength="100" value="{{ old('title') }}" class="w-full rounded-lg bg-[#263543] border border-[#344453] text-[#D6DEE6] text-sm px-3 py-2.5 focus:border-gold">
                                @error('title')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-xs font-black uppercase tracking-widest text-[#A8B5C1] mb-1.5">Your review *</label>
                                <textarea name="body" rows="4" required minlength="10" maxlength="2000" class="w-full rounded-lg bg-[#263543] border border-[#344453] text-[#D6DEE6] text-sm px-3 py-2.5 focus:border-gold">{{ old('body') }}</textarea>
                                @error('body')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                            </div>
                            <button type="submit" class="px-5 py-2.5 bg-gold hover:bg-gold-hover text-[#D6DEE6] font-black rounded-lg text-sm">Post review</button>
                        </form>
                    </div>
                @endif
            @else
                <div class="bg-[#171717] border border-[#344453] rounded-2xl p-6 text-center">
                    <p class="text-sm text-[#A8B5C1]">
                        <a href="{{ route('login') }}" class="text-gold hover:text-gold-hover font-bold">Login</a> to write a review
                    </p>
                </div>
            @endauth

            {{-- REVIEWS LIST --}}
            <div class="space-y-3">
                @forelse ($reviews as $review)
                    <div class="bg-[#171717] border border-[#344453] rounded-2xl p-5">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gold text-[#D6DEE6] flex items-center justify-center font-black">{{ Str::substr($review->user->name ?? '?', 0, 1) }}</div>
                                <div>
                                    <div class="font-bold text-[#D6DEE6] text-sm">{{ $review->user->name ?? 'Anonymous' }}</div>
                                    <div class="text-xs text-[#A8B5C1]">{{ $review->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                            @if ($review->is_verified_purchase)
                                <span class="text-[10px] font-black uppercase tracking-wider px-2 py-0.5 bg-gold/10 text-gold border border-gold/30 rounded">Verified</span>
                            @endif
                        </div>
                        <div class="flex gap-0.5 mt-3">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $review->rating)
                                    <svg class="w-4 h-4 text-gold" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @else
                                    <svg class="w-4 h-4 text-[#A8B5C1]" fill="none" stroke="currentColor" viewBox="0 0 20 20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                @endif
                            @endfor
                        </div>
                        @if ($review->title)<div class="font-bold text-[#D6DEE6] mt-2">{{ $review->title }}</div>@endif
                        <p class="text-sm text-[#D6DEE6] mt-1 whitespace-pre-line">{{ $review->body }}</p>

                        @if ($review->vendor_reply)
                            <div class="mt-3 p-3 rounded-lg bg-gold/5 border-l-2 border-gold">
                                <div class="text-[10px] font-black uppercase tracking-widest text-gold mb-1">Vendor reply &middot; {{ $review->vendor_replied_at?->diffForHumans() }}</div>
                                <p class="text-sm text-[#D6DEE6] whitespace-pre-line">{{ $review->vendor_reply }}</p>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="bg-[#171717] border border-[#344453] rounded-2xl p-12 text-center text-[#A8B5C1]">
                        No reviews yet. Be the first!
                    </div>
                @endforelse
            </div>
            <div>{{ $reviews->links() }}</div>
        </div>

        {{-- SIDEBAR PRODUCT CARD --}}
        <div class="lg:col-span-1">
            <div class="bg-[#171717] border border-[#344453] rounded-2xl p-4 space-y-3 lg:sticky lg:top-24">
                @if ($product->image)
                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-full aspect-square object-cover rounded-lg">
                @endif
                <div class="font-bold text-[#D6DEE6]">{{ $product->name }}</div>
                <div class="text-xl font-black text-gold">{{ number_format((float) $product->price, 2) }} <span class="text-xs text-[#A8B5C1]">LYD</span></div>
                <a href="{{ route('shop.products.show', $product) }}" class="block text-center px-3 py-2.5 bg-gold hover:bg-gold-hover text-[#D6DEE6] font-black rounded-lg text-sm">View product</a>
            </div>
        </div>
    </div>
</x-layouts.shop>