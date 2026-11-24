<x-layouts.dashboard :title="$product->exists ? 'Edit Product' : 'New Product'">
    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('vendor.products.index') }}" class="text-xs font-bold text-gray-500 hover:text-lime-400">&larr; Back to products</a>
            <h1 class="text-3xl font-black text-white mt-2">{{ $product->exists ? 'Edit product' : 'New product' }}</h1>
        </div>

        <form method="POST"
              action="{{ $product->exists ? route('vendor.products.update', $product) : route('vendor.products.store') }}"
              enctype="multipart/form-data"
              class="bg-[#171717] border border-gray-800 rounded-2xl p-6 space-y-5">
            @csrf
            @if ($product->exists) @method('PUT') @endif

            <div>
                <label class="block text-xs font-black uppercase tracking-widest text-gray-500 mb-2">Product image</label>
                @if ($product->image)
                    <img src="{{ Storage::url($product->image) }}" alt="" class="mb-3 w-32 h-32 object-cover rounded-lg border border-gray-800">
                @endif
                <input type="file" name="image" accept="image/jpeg,image/png,image/webp"
                       class="block w-full text-sm text-gray-300 border border-gray-800 rounded-lg bg-gray-900 p-2 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:bg-lime-400 file:text-black file:font-black file:text-xs">
                <p class="mt-1 text-xs text-gray-500">JPG, PNG or WebP. Max 2MB.</p>
                @error('image')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-black uppercase tracking-widest text-gray-500 mb-1.5">Product name *</label>
                <input name="name" type="text" required maxlength="150" value="{{ old('name', $product->name) }}"
                       class="w-full rounded-lg bg-gray-900 border border-gray-800 text-white text-sm px-3 py-2.5 focus:border-lime-400 focus:ring-2 focus:ring-lime-400/20">
                @error('name')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-black uppercase tracking-widest text-gray-500 mb-1.5">Category *</label>
                <select name="category_id" required class="w-full rounded-lg bg-gray-900 border border-gray-800 text-white text-sm px-3 py-2.5 focus:border-lime-400">
                    <option value="">— Select a category —</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(old('category_id', $product->category_id) == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('category_id')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-black uppercase tracking-widest text-gray-500 mb-1.5">Description</label>
                <textarea name="description" rows="4" maxlength="5000"
                          class="w-full rounded-lg bg-gray-900 border border-gray-800 text-white text-sm px-3 py-2.5 focus:border-lime-400">{{ old('description', $product->description) }}</textarea>
                @error('description')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-gray-500 mb-1.5">Price (LYD) *</label>
                    <input name="price" type="number" step="0.01" min="0.01" required value="{{ old('price', $product->price) }}"
                           class="w-full rounded-lg bg-gray-900 border border-gray-800 text-white text-sm px-3 py-2.5 focus:border-lime-400">
                    @error('price')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-gray-500 mb-1.5">Stock *</label>
                    <input name="stock" type="number" min="0" required value="{{ old('stock', $product->stock ?? 0) }}"
                           class="w-full rounded-lg bg-gray-900 border border-gray-800 text-white text-sm px-3 py-2.5 focus:border-lime-400">
                    @error('stock')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-gray-500 mb-1.5">SKU</label>
                    <input name="sku" type="text" maxlength="64" value="{{ old('sku', $product->sku) }}"
                           class="w-full rounded-lg bg-gray-900 border border-gray-800 text-white text-sm px-3 py-2.5 focus:border-lime-400">
                    @error('sku')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                </div>
            </div>

            <label class="inline-flex items-center gap-2 cursor-pointer">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}
                       class="w-4 h-4 rounded bg-gray-900 border-gray-700 text-lime-400 focus:ring-lime-400">
                <span class="text-sm font-bold text-gray-300">Active (visible to customers)</span>
            </label>

            <div class="flex items-center justify-between pt-4 border-t border-gray-800">
                <a href="{{ route('vendor.products.index') }}" class="text-sm font-bold text-gray-500 hover:text-lime-400">Cancel</a>
                <button type="submit" class="px-6 py-2.5 bg-lime-400 hover:bg-lime-300 text-black font-black rounded-lg text-sm">
                    {{ $product->exists ? 'Save changes' : 'Create product' }}
                </button>
            </div>
        </form>
    </div>
</x-layouts.dashboard>