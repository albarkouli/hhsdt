<x-layouts.shop :title="'WESALKOM'">
    <div class="text-center py-16">
        <h1 class="text-4xl font-bold text-[#D6DEE6] mb-4">Welcome to WESALKOM</h1>
        <p class="text-lg text-[#A8B5C1] mb-8 max-w-xl mx-auto">
            Discover products from trusted vendors across Libya, all in one place.
        </p>
        <a href="{{ route('shop.products.index') }}"
           class="inline-flex items-center px-6 py-3 bg-indigo-600 text-[#D6DEE6] font-medium rounded-lg hover:bg-indigo-700">
            Browse the shop &rarr;
        </a>
    </div>
</x-layouts.shop>