<x-layouts.shop :title="'About'">
    <div class="max-w-3xl mx-auto">
        <header class="text-center mb-12">
            <span class="inline-block text-xs uppercase tracking-widest text-lime-400 font-black mb-3">Our story</span>
            <h1 class="text-4xl sm:text-5xl font-black text-white mb-4">About WESALKOM</h1>
            <p class="text-lg text-gray-400">Connecting Libyan vendors with customers, one order at a time.</p>
        </header>

        <div class="space-y-6 text-gray-300 leading-relaxed">
            <p>
                WESALKOM is a homegrown online marketplace built to serve Libyan vendors and shoppers. Our mission is simple: give local businesses a modern, trusted platform to sell online â€” and give customers a single destination to discover products from across the country.
            </p>

            <h2 class="text-2xl font-black text-white pt-6">What we offer</h2>
            <ul class="space-y-2 list-none">
                <li class="flex items-start gap-3"><span class="text-lime-400 font-black mt-0.5">&#10003;</span><span>Verified vendors from across Libya</span></li>
                <li class="flex items-start gap-3"><span class="text-lime-400 font-black mt-0.5">&#10003;</span><span>Cash on Delivery â€” the payment method Libyans trust most</span></li>
                <li class="flex items-start gap-3"><span class="text-lime-400 font-black mt-0.5">&#10003;</span><span>Fast, transparent order tracking</span></li>
                <li class="flex items-start gap-3"><span class="text-lime-400 font-black mt-0.5">&#10003;</span><span>Direct communication with store owners</span></li>
                <li class="flex items-start gap-3"><span class="text-lime-400 font-black mt-0.5">&#10003;</span><span>Zero setup fees for vendors</span></li>
            </ul>

            <h2 class="text-2xl font-black text-white pt-6">Our values</h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-[#171717] border border-gray-800 rounded-xl p-5 text-center">
                    <div class="w-12 h-12 rounded-full bg-lime-400/10 border border-lime-400/30 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-lime-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <h3 class="font-black text-white mb-1">Trust</h3>
                    <p class="text-xs text-gray-500">Every vendor is reviewed before going live.</p>
                </div>
                <div class="bg-[#171717] border border-gray-800 rounded-xl p-5 text-center">
                    <div class="w-12 h-12 rounded-full bg-lime-400/10 border border-lime-400/30 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-lime-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <h3 class="font-black text-white mb-1">Simplicity</h3>
                    <p class="text-xs text-gray-500">A clean experience for buyers and sellers.</p>
                </div>
                <div class="bg-[#171717] border border-gray-800 rounded-xl p-5 text-center">
                    <div class="w-12 h-12 rounded-full bg-lime-400/10 border border-lime-400/30 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-lime-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="font-black text-white mb-1">Local first</h3>
                    <p class="text-xs text-gray-500">Built for Libya, by understanding Libyan needs.</p>
                </div>
            </div>
        </div>

        <div class="mt-12 text-center">
            <a href="{{ route('shop.products.index') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-lime-400 hover:bg-lime-300 text-black font-black rounded-xl text-sm shadow-[0_0_30px_rgba(163,230,53,0.25)]">
                Start shopping &rarr;
            </a>
        </div>
    </div>
</x-layouts.shop>