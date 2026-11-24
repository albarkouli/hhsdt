<x-layouts.dashboard :title="'Categories'">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-black text-[#D6DEE6]">Categories</h1>
                <p class="text-sm text-[#A8B5C1] mt-1">Manage product categories</p>
            </div>
            <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gold hover:bg-gold-hover text-[#D6DEE6] font-black rounded-lg text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                New category
            </a>
        </div>

        @if (session('status'))
            <div class="rounded-xl border border-gold/30 bg-gold/10 p-3 text-sm text-gold-hover">{{ session('status') }}</div>
        @endif

        <div class="bg-[#171717] border border-[#344453] rounded-2xl overflow-hidden">
            <table class="min-w-full divide-y divide-gray-800">
                <thead class="bg-[#263543]/50">
                    <tr>
                        <th class="px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-[#A8B5C1]">Name</th>
                        <th class="px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-[#A8B5C1]">Slug</th>
                        <th class="px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-[#A8B5C1]">Status</th>
                        <th class="px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-[#A8B5C1]">Sort</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @forelse ($categories as $category)
                        <tr class="hover:bg-[#263543]/30">
                            <td class="px-4 py-3">
                                <div class="font-bold text-[#D6DEE6] text-sm">{{ $category->name }}</div>
                                @if ($category->description)<div class="text-[10px] text-[#A8B5C1] line-clamp-1">{{ $category->description }}</div>@endif
                            </td>
                            <td class="px-4 py-3 text-sm text-[#A8B5C1] font-mono">/{{ $category->slug }}</td>
                            <td class="px-4 py-3">
                                @if ($category->is_active)
                                    <span class="px-2 py-0.5 text-[10px] font-black uppercase tracking-wider rounded bg-gold/10 text-gold border border-gold/30">Active</span>
                                @else
                                    <span class="px-2 py-0.5 text-[10px] font-black uppercase tracking-wider rounded bg-[#2D3D4C]/50 text-[#A8B5C1] border border-[#344453]">Inactive</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-[#A8B5C1]">{{ $category->sort_order }}</td>
                            <td class="px-4 py-3">
                                <div class="flex gap-1 justify-end">
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="px-2 py-1 text-[10px] font-black uppercase bg-indigo-500 text-[#D6DEE6] rounded hover:bg-indigo-400">Edit</a>
                                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Delete this category?')">
                                        @csrf @method('DELETE')
                                        <button class="px-2 py-1 text-[10px] font-black uppercase bg-red-500/20 text-red-400 border border-red-500/30 rounded hover:bg-red-500/30">Del</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-8 text-center text-sm text-[#A8B5C1]">No categories yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div>{{ $categories->links() }}</div>
    </div>
</x-layouts.dashboard>