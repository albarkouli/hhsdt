<x-layouts.shop :title="'Contact'">
    <div class="max-w-2xl mx-auto">
        <header class="text-center mb-10">
            <span class="inline-block text-xs uppercase tracking-widest text-gold font-black mb-3">Contact</span>
            <h1 class="text-4xl sm:text-5xl font-black text-[#D6DEE6] mb-3">Get in touch</h1>
            <p class="text-lg text-[#A8B5C1]">We'd love to hear from you.</p>
        </header>

        @if (session('contact_status'))
            <div class="mb-6 rounded-xl border border-gold/30 bg-gold/10 p-4 text-sm text-gold-hover">{{ session('contact_status') }}</div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
            <div class="bg-[#171717] border border-[#344453] rounded-xl p-5">
                <div class="w-10 h-10 rounded-lg bg-gold/10 border border-gold/30 flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <div class="font-black text-[#D6DEE6] mb-1">Email</div>
                <a href="mailto:hello@wesalkom.ly" class="text-sm text-gold hover:text-gold-hover">hello@wesalkom.ly</a>
            </div>
            <div class="bg-[#171717] border border-[#344453] rounded-xl p-5">
                <div class="w-10 h-10 rounded-lg bg-gold/10 border border-gold/30 flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div class="font-black text-[#D6DEE6] mb-1">Location</div>
                <p class="text-sm text-[#A8B5C1]">Tripoli, Libya</p>
            </div>
        </div>

        <div class="bg-[#171717] border border-[#344453] rounded-2xl p-6">
            <h2 class="font-black text-[#D6DEE6] text-lg mb-2">Send us a message</h2>
            <p class="text-sm text-[#A8B5C1] mb-6">For vendor inquiries, support, or partnerships. We'll get back within 48 hours.</p>

            <form method="POST" action="{{ route('shop.contact.store') }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-[#A8B5C1] mb-1.5">Name *</label>
                        <input name="name" type="text" required maxlength="100" value="{{ old('name', auth()->user()->name ?? '') }}"
                               class="w-full rounded-lg bg-[#263543] border border-[#344453] text-[#D6DEE6] text-sm px-3 py-2.5 focus:border-gold focus:ring-2 focus:ring-gold/20">
                        @error('name')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-[#A8B5C1] mb-1.5">Email *</label>
                        <input name="email" type="email" required maxlength="150" value="{{ old('email', auth()->user()->email ?? '') }}"
                               class="w-full rounded-lg bg-[#263543] border border-[#344453] text-[#D6DEE6] text-sm px-3 py-2.5 focus:border-gold focus:ring-2 focus:ring-gold/20">
                        @error('email')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-[#A8B5C1] mb-1.5">Subject</label>
                    <input name="subject" type="text" maxlength="200" value="{{ old('subject') }}" placeholder="Optional"
                           class="w-full rounded-lg bg-[#263543] border border-[#344453] text-[#D6DEE6] text-sm px-3 py-2.5 focus:border-gold">
                </div>
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-[#A8B5C1] mb-1.5">Message *</label>
                    <textarea name="message" rows="5" required maxlength="5000" placeholder="How can we help?"
                              class="w-full rounded-lg bg-[#263543] border border-[#344453] text-[#D6DEE6] text-sm px-3 py-2.5 focus:border-gold">{{ old('message') }}</textarea>
                    @error('message')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="w-full bg-gold hover:bg-gold-hover text-[#D6DEE6] font-black py-3 rounded-lg text-sm shadow-[0_0_30px_rgba(163,230,53,0.2)]">Send message</button>
            </form>
        </div>
    </div>
</x-layouts.shop>