<x-layouts.dashboard :title="$category->exists ? 'Edit Category' : 'New Category'">
    <div class="max-w-2xl mx-auto bg-[#171717] border border-[#344453] rounded-2xl p-6">
        <h1 class="text-2xl font-black text-[#D6DEE6] mb-6">{{ $category->exists ? 'Edit category' : 'New category' }}</h1>

        <form method="POST" action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}" class="space-y-5">
            @csrf
            @if ($category->exists) @method('PUT') @endif

            <div>
                <label class="block text-xs font-black uppercase tracking-widest text-[#A8B5C1] mb-1.5">Name *</label>
                <input name="name" type="text" required maxlength="100" value="{{ old('name', $category->name) }}"
                       class="w-full rounded-lg bg-[#263543] border border-[#344453] text-[#D6DEE6] text-sm px-3 py-2.5 focus:border-gold">
                @error('name')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-black uppercase tracking-widest text-[#A8B5C1] mb-1.5">Description</label>
                <textarea name="description" rows="3" maxlength="1000" class="w-full rounded-lg bg-[#263543] border border-[#344453] text-[#D6DEE6] text-sm px-3 py-2.5 focus:border-gold">{{ old('description', $category->description) }}</textarea>
                @error('description')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-[#A8B5C1] mb-1.5">Sort order</label>
                    <input name="sort_order" type="number" min="0" max="9999" value="{{ old('sort_order', $category->sort_order ?? 0) }}"
                           class="w-full rounded-lg bg-[#263543] border border-[#344453] text-[#D6DEE6] text-sm px-3 py-2.5 focus:border-gold">
                    @error('sort_order')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                </div>
                <div class="flex items-end pb-3">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}
                               class="w-4 h-4 rounded bg-[#263543] border-[#344453] text-gold focus:ring-gold">
                        <span class="text-sm font-bold text-[#D6DEE6]">Active</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-[#344453]">
                <a href="{{ route('admin.categories.index') }}" class="text-sm font-bold text-[#A8B5C1] hover:text-gold">&larr; Back</a>
                <button type="submit" class="px-6 py-2.5 bg-gold hover:bg-gold-hover text-[#D6DEE6] font-black rounded-lg text-sm">{{ $category->exists ? 'Save changes' : 'Create category' }}</button>
            </div>
        </form>
    </div>
</x-layouts.dashboard>