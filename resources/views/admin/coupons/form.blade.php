<x-layouts.dashboard :title="$coupon->exists ? 'Edit Coupon' : 'New Coupon'">
    <div class="max-w-3xl mx-auto bg-[#171717] border border-[#344453] rounded-2xl p-6">
        <h1 class="text-2xl font-black text-[#D6DEE6] mb-6">{{ $coupon->exists ? 'Edit coupon' : 'New coupon' }}</h1>

        <form method="POST" action="{{ $coupon->exists ? route('admin.coupons.update', $coupon) : route('admin.coupons.store') }}" class="space-y-5">
            @csrf
            @if ($coupon->exists) @method('PUT') @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-[#A8B5C1] mb-1.5">Code *</label>
                    <input name="code" type="text" required maxlength="30" style="text-transform:uppercase" value="{{ old('code', $coupon->code) }}" placeholder="SUMMER2026"
                           class="w-full rounded-lg bg-[#263543] border border-[#344453] text-[#D6DEE6] text-sm font-mono px-3 py-2.5 focus:border-gold focus:ring-2 focus:ring-gold/20">
                    @error('code')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                    <p class="mt-1 text-[10px] text-[#A8B5C1]">A-Z, 0-9, -, _ only</p>
                </div>
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-[#A8B5C1] mb-1.5">Name *</label>
                    <input name="name" type="text" required maxlength="100" value="{{ old('name', $coupon->name) }}" placeholder="Summer Sale 2026"
                           class="w-full rounded-lg bg-[#263543] border border-[#344453] text-[#D6DEE6] text-sm px-3 py-2.5 focus:border-gold focus:ring-2 focus:ring-gold/20">
                    @error('name')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-xs font-black uppercase tracking-widest text-[#A8B5C1] mb-1.5">Description</label>
                <textarea name="description" rows="2" maxlength="1000" class="w-full rounded-lg bg-[#263543] border border-[#344453] text-[#D6DEE6] text-sm px-3 py-2.5 focus:border-gold">{{ old('description', $coupon->description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-[#A8B5C1] mb-1.5">Type *</label>
                    <select name="type" required class="w-full rounded-lg bg-[#263543] border border-[#344453] text-[#D6DEE6] text-sm px-3 py-2.5 focus:border-gold">
                        <option value="percent" @selected(old('type', $coupon->type) === 'percent')>Percent (%)</option>
                        <option value="fixed" @selected(old('type', $coupon->type) === 'fixed')>Fixed amount (LYD)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-[#A8B5C1] mb-1.5">Value *</label>
                    <input name="value" type="number" step="0.01" min="0.01" required value="{{ old('value', $coupon->value) }}"
                           class="w-full rounded-lg bg-[#263543] border border-[#344453] text-[#D6DEE6] text-sm px-3 py-2.5 focus:border-gold">
                    @error('value')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-[#A8B5C1] mb-1.5">Min order (LYD)</label>
                    <input name="min_order" type="number" step="0.01" min="0" value="{{ old('min_order', $coupon->min_order ?? 0) }}"
                           class="w-full rounded-lg bg-[#263543] border border-[#344453] text-[#D6DEE6] text-sm px-3 py-2.5 focus:border-gold">
                </div>
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-[#A8B5C1] mb-1.5">Max discount (LYD)</label>
                    <input name="max_discount" type="number" step="0.01" min="0" value="{{ old('max_discount', $coupon->max_discount) }}" placeholder="No cap"
                           class="w-full rounded-lg bg-[#263543] border border-[#344453] text-[#D6DEE6] text-sm px-3 py-2.5 focus:border-gold">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-[#A8B5C1] mb-1.5">Max total uses</label>
                    <input name="max_uses" type="number" min="1" value="{{ old('max_uses', $coupon->max_uses) }}" placeholder="Unlimited"
                           class="w-full rounded-lg bg-[#263543] border border-[#344453] text-[#D6DEE6] text-sm px-3 py-2.5 focus:border-gold">
                </div>
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-[#A8B5C1] mb-1.5">Max uses per user *</label>
                    <input name="max_uses_per_user" type="number" min="1" max="100" required value="{{ old('max_uses_per_user', $coupon->max_uses_per_user ?? 1) }}"
                           class="w-full rounded-lg bg-[#263543] border border-[#344453] text-[#D6DEE6] text-sm px-3 py-2.5 focus:border-gold">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-[#A8B5C1] mb-1.5">Starts at</label>
                    <p class="text-[10px] text-[#A8B5C1] mb-1">Leave empty to start immediately</p>
                    <input name="starts_at" type="datetime-local" value="{{ old('starts_at', $coupon->starts_at?->format('Y-m-d\TH:i')) }}"
                           class="w-full rounded-lg bg-[#263543] border border-[#344453] text-[#D6DEE6] text-sm px-3 py-2.5 focus:border-gold">
                </div>
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-[#A8B5C1] mb-1.5">Expires at</label>
                    <p class="text-[10px] text-[#A8B5C1] mb-1">Leave empty for no expiration</p>
                    <input name="expires_at" type="datetime-local" value="{{ old('expires_at', $coupon->expires_at?->format('Y-m-d\TH:i')) }}"
                           class="w-full rounded-lg bg-[#263543] border border-[#344453] text-[#D6DEE6] text-sm px-3 py-2.5 focus:border-gold">
                    @error('expires_at')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                </div>
            </div>

            <label class="inline-flex items-center gap-2 cursor-pointer">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $coupon->is_active ?? true) ? 'checked' : '' }}
                       class="w-4 h-4 rounded bg-[#263543] border-[#344453] text-gold focus:ring-gold">
                <span class="text-sm font-bold text-[#D6DEE6]">Active</span>
            </label>

            <div class="flex items-center justify-between pt-4 border-t border-[#344453]">
                <a href="{{ route('admin.coupons.index') }}" class="text-sm font-bold text-[#A8B5C1] hover:text-gold">&larr; Back</a>
                <button type="submit" class="px-6 py-2.5 bg-gold hover:bg-gold-hover text-[#D6DEE6] font-black rounded-lg text-sm">{{ $coupon->exists ? 'Save changes' : 'Create coupon' }}</button>
            </div>
        </form>
    </div>
</x-layouts.dashboard>