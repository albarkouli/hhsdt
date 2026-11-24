<x-layouts.dashboard :title="'Store Settings'">
    <div class="max-w-3xl mx-auto space-y-6">
        <div>
            <h1 class="text-3xl font-black text-white">Store settings</h1>
            <p class="text-sm text-gray-500 mt-1">Manage your store's public profile</p>
        </div>

        @if (session('status'))
            <div class="rounded-xl border border-lime-400/30 bg-lime-400/10 p-3 text-sm text-lime-300">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('vendor.settings.update') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf @method('PUT')

            <div class="bg-[#171717] border border-gray-800 rounded-2xl p-6 space-y-4">
                <h2 class="font-black text-white">Branding</h2>
                <div class="flex items-center gap-6">
                    @if ($vendor->logo)
                        <img src="{{ Storage::url($vendor->logo) }}" alt="" class="w-20 h-20 rounded-2xl object-cover border border-gray-800">
                    @else
                        <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-lime-400 to-emerald-500 flex items-center justify-center text-black text-3xl font-black">{{ Str::substr($vendor->name, 0, 1) }}</div>
                    @endif
                    <div class="flex-1">
                        <label class="block text-xs font-black uppercase tracking-widest text-gray-500 mb-2">Store logo</label>
                        <input type="file" name="logo" accept="image/jpeg,image/png,image/webp"
                               class="block w-full text-sm text-gray-300 border border-gray-800 rounded-lg bg-gray-900 p-2 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:bg-lime-400 file:text-black file:font-black file:text-xs">
                        <p class="mt-1 text-xs text-gray-500">JPG, PNG or WebP. Max 2MB. Square looks best.</p>
                        @error('logo')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <div class="bg-[#171717] border border-gray-800 rounded-2xl p-6 space-y-4">
                <h2 class="font-black text-white">Store info</h2>
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-gray-500 mb-1.5">Store name *</label>
                    <input name="name" type="text" required maxlength="100" value="{{ old('name', $vendor->name) }}"
                           class="w-full rounded-lg bg-gray-900 border border-gray-800 text-white text-sm px-3 py-2.5 focus:border-lime-400">
                    @error('name')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                    <p class="mt-1 text-xs text-gray-500">Public URL: <code class="bg-gray-900 px-1.5 py-0.5 rounded text-lime-400">/shop/stores/{{ $vendor->slug }}</code> (permanent)</p>
                </div>
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-gray-500 mb-1.5">Description</label>
                    <textarea name="description" rows="3" maxlength="2000" class="w-full rounded-lg bg-gray-900 border border-gray-800 text-white text-sm px-3 py-2.5 focus:border-lime-400">{{ old('description', $vendor->description) }}</textarea>
                    @error('description')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-gray-500 mb-1.5">Phone *</label>
                        <input name="phone" type="text" required maxlength="30" value="{{ old('phone', $vendor->phone) }}"
                               class="w-full rounded-lg bg-gray-900 border border-gray-800 text-white text-sm px-3 py-2.5 focus:border-lime-400">
                        @error('phone')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-gray-500 mb-1.5">Store email</label>
                        <input name="email" type="email" maxlength="150" value="{{ old('email', $vendor->email) }}"
                               class="w-full rounded-lg bg-gray-900 border border-gray-800 text-white text-sm px-3 py-2.5 focus:border-lime-400">
                        @error('email')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-gray-500 mb-1.5">City *</label>
                        <input name="city" type="text" required maxlength="100" value="{{ old('city', $vendor->city) }}"
                               class="w-full rounded-lg bg-gray-900 border border-gray-800 text-white text-sm px-3 py-2.5 focus:border-lime-400">
                        @error('city')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-gray-500 mb-1.5">Address</label>
                        <input name="address" type="text" maxlength="255" value="{{ old('address', $vendor->address) }}"
                               class="w-full rounded-lg bg-gray-900 border border-gray-800 text-white text-sm px-3 py-2.5 focus:border-lime-400">
                        @error('address')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <a href="{{ route('vendor.dashboard') }}" class="text-sm font-bold text-gray-500 hover:text-lime-400">&larr; Back to dashboard</a>
                <button type="submit" class="px-6 py-2.5 bg-lime-400 hover:bg-lime-300 text-black font-black rounded-lg text-sm">Save changes</button>
            </div>
        </form>
    </div>
</x-layouts.dashboard>