<x-layouts.dashboard :title="'Categories'">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-black text-white">Categories</h1>
                <p class="text-sm text-gray-500 mt-1">Manage product categories</p>
            </div>
            <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-lime-400 hover:bg-lime-300 text-black font-black rounded-lg text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                New category
            </a>
        </div>

        @if (session('status'))
            <div class="rounded-xl border border-lime-400/30 bg-lime-400/10 p-3 text-sm text-lime-300">{{ session('status') }}</div>
        @endif

        <div class="bg-[#171717] border border-gray-800 rounded-2xl overflow-hidden">
            <table class="min-w-full divide-y divide-gray-800">
                <thead class="bg-gray-900/50">
                    <tr>
                        <th class="px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-gray-500">Name</th>
                        <th class="px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-gray-500">Slug</th>
                        <th class="px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-gray-500">Status</th>
                        <th class="px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-gray-500">Sort</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @forelse ($categories as $category)
                        <tr class="hover:bg-gray-900/30">
                            <td class="px-4 py-3">
                                <div class="font-bold text-white text-sm">{{ $category->name }}</div>
                                @if ($category->description)<div class="text-[10px] text-gray-500 line-clamp-1">{{ $category->description }}</div>@endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-500 font-mono">/{{ $category->slug }}</td>
                            <td class="px-4 py-3">
                                @if ($category->is_active)
                                    <span class="px-2 py-0.5 text-[10px] font-black uppercase tracking-wider rounded bg-lime-400/10 text-lime-400 border border-lime-400/30">Active</span>
                                @else
                                    <span class="px-2 py-0.5 text-[10px] font-black uppercase tracking-wider rounded bg-gray-700/50 text-gray-400 border border-gray-700">Inactive</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-400">{{ $category->sort_order }}</td>
                            <td class="px-4 py-3">
                                <div class="flex gap-1 justify-end">
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="px-2 py-1 text-[10px] font-black uppercase bg-indigo-500 text-white rounded hover:bg-indigo-400">Edit</a>
                                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Delete this category?')">
                                        @csrf @method('DELETE')
                                        <button class="px-2 py-1 text-[10px] font-black uppercase bg-red-500/20 text-red-400 border border-red-500/30 rounded hover:bg-red-500/30">Del</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-8 text-center text-sm text-gray-500">No categories yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div>{{ $categories->links() }}</div>
    </div>
</x-layouts.dashboard>