@props([
    'title',
    'description',
])

<div class="flex w-full flex-col text-center mb-6">
    <h1 class="text-2xl font-black text-white">{{ $title }}</h1>
    @if ($description)
        <p class="text-sm text-gray-400 mt-2">{{ $description }}</p>
    @endif
</div>