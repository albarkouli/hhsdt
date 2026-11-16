<x-layouts.dashboard :title="$category->exists ? 'Edit Category' : 'New Category'">
    <div class="max-w-2xl mx-auto bg-[#171717] border border-gray-800 rounded-2xl p-6">
        <h1 class="text-2xl font-black text-white mb-6">{{ $category->exists ? 'Edit category' : 'New category' }}</h1>

        <form method="POST" action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}" class="space-y-5">
            @csrf
            @if ($category->exists) @method('PUT') @endif

            <div>
                <label class="block text-xs font-black uppercase tracking-widest text-gray-500 mb-1.5">Name *</label>
                <input name="name" type="text" required maxlength="100" value="{{ old('name', $category->name) }}"
                       class="w-full rounded-lg bg-gray-900 border border-gray-800 text-white text-sm px-3 py-2.5 focus:border-lime-400">
                @error('name')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-black uppercase tracking-widest text-gray-500 mb-1.5">Description</label>
                <textarea name="description" rows="3" maxlength="1000" class="w-full rounded-lg bg-gray-900 border border-gray-800 text-white text-sm px-3 py-2.5 focus:border-lime-400">{{ old('description', $category->description) }}</textarea>
                @error('description')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-gray-500 mb-1.5">Sort order</label>
                    <input name="sort_order" type="number" min="0" max="9999" value="{{ old('sort_order', $category->sort_order ?? 0) }}"
                           class="w-full rounded-lg bg-gray-900 border border-gray-800 text-white text-sm px-3 py-2.5 focus:border-lime-400">
                    @error('sort_order')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                </div>
                <div class="flex items-end pb-3">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}
                               class="w-4 h-4 rounded bg-gray-900 border-gray-700 text-lime-400 focus:ring-lime-400">
                        <span class="text-sm font-bold text-gray-300">Active</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-gray-800">
                <a href="{{ route('admin.categories.index') }}" class="text-sm font-bold text-gray-500 hover:text-lime-400">&larr; Back</a>
                <button type="submit" class="px-6 py-2.5 bg-lime-400 hover:bg-lime-300 text-black font-black rounded-lg text-sm">{{ $category->exists ? 'Save changes' : 'Create category' }}</button>
            </div>
        </form>
    </div>
</x-layouts.dashboard>