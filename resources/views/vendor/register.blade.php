<x-layouts.dashboard :title="'Open a store'">
    <div class="max-w-2xl mx-auto bg-[#263543] rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold text-[#D6DEE6] mb-1">Open your store</h1>
        <p class="text-sm text-[#A8B5C1] mb-6">
            Tell us about your business. Your store will be reviewed by the
            platform team before you can publish products.
        </p>

        @if (session('status'))
            <div class="mb-4 rounded border border-green-300 bg-green-50 p-3 text-sm text-green-800">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('vendor.register.store') }}" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-[#A8B5C1]">Store name <span class="text-red-500">*</span></label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required maxlength="100"
                       class="mt-1 block w-full rounded border-[#344453] shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-[#A8B5C1]">Description</label>
                <textarea id="description" name="description" rows="4" maxlength="2000"
                          class="mt-1 block w-full rounded border-[#344453] shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="phone" class="block text-sm font-medium text-[#A8B5C1]">Phone <span class="text-red-500">*</span></label>
                    <input id="phone" name="phone" type="text" value="{{ old('phone') }}" required maxlength="30"
                           class="mt-1 block w-full rounded border-[#344453] shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="09XXXXXXXX">
                    @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-[#A8B5C1]">Store email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" maxlength="150"
                           class="mt-1 block w-full rounded border-[#344453] shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="city" class="block text-sm font-medium text-[#A8B5C1]">City <span class="text-red-500">*</span></label>
                    <input id="city" name="city" type="text" value="{{ old('city') }}" required maxlength="100"
                           class="mt-1 block w-full rounded border-[#344453] shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('city')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="address" class="block text-sm font-medium text-[#A8B5C1]">Address</label>
                    <input id="address" name="address" type="text" value="{{ old('address') }}" maxlength="255"
                           class="mt-1 block w-full rounded border-[#344453] shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('address')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex items-center justify-between pt-2">
                <a href="{{ route('customer.dashboard') }}" class="text-sm text-[#A8B5C1] hover:underline">
                    أ¢â€ ع¯ Back to dashboard
                </a>
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 text-[#D6DEE6] text-sm font-medium rounded hover:bg-indigo-700">
                    Submit for review
                </button>
            </div>
        </form>
    </div>
</x-layouts.dashboard>
