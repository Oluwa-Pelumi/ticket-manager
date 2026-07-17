<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-sky-950 flex items-center justify-center shadow-lg border border-white/20">
                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
            <div class="flex flex-col">
                <h2 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">
                    Categories
                </h2>
                <span class="text-[10px] font-black tracking-[0.3em] text-slate-400">
                    Manage Support Topics
                </span>
            </div>
        </div>
    </x-slot>

    <x-slot name="title">Manage Categories</x-slot>


    <div class="max-w-7xl mx-auto py-2 px-4 sm:px-6">

        {{-- Flash messages --}}
        <x-flash-handler />

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">

            {{-- ── Create / Edit form ── --}}
            <div class="lg:col-span-1">
                <div class="fauna-panel p-6 sm:p-8 lg:sticky lg:top-24">

                    <h3 class="text-xl font-black text-slate-900 dark:text-white mb-6 tracking-tight">
                        {{ isset($editingCategory) ? 'Edit Category' : 'Create New Category' }}
                    </h3>

                    <form
                        method="POST"
                        action="{{ isset($editingCategory)
                            ? route('admin.categories.update', $editingCategory->id)
                            : route('admin.categories.store') }}"
                        class="space-y-6"
                        x-data="{ processing: false }" @submit="processing = true"
                    >
                        @csrf
                        @if (isset($editingCategory))
                            @method('PATCH')
                        @endif

                        {{-- Category Name --}}
                        <div class="space-y-2">
                            <label class="text-xs font-black tracking-widest text-slate-600 dark:text-slate-400">
                                Category Name
                            </label>
                            <input
                                type="text"
                                name="name"
                                value="{{ old('name', $editingCategory->name ?? '') }}"
                                class="w-full px-4 py-3 rounded-xl bg-white dark:bg-[#1e293b] border border-sky-950/10 dark:border-[#1e3a5f] text-slate-900 dark:text-white focus:ring-2 focus:ring-sky-400 transition-all outline-none font-medium"
                                placeholder="e.g. Transcript Request"
                                required
                            />
                            @error('name')
                                <p class="text-rose-500 text-[10px] font-bold">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Actions --}}
                        <div class="flex flex-col gap-3">
                            <button
                                type="submit"
                                x-bind:disabled="processing"
                                class="w-full py-4 rounded-xl bg-sky-950 text-white font-black text-sm tracking-widest shadow-lg hover:bg-sky-800 hover:text-white transition-all disabled:opacity-50 flex items-center justify-center gap-2"
                            >
                                <template x-if="processing">
                                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                                    </svg>
                                </template>
                                <span x-text="processing ? '{{ isset($editingCategory) ? 'Updating...' : 'Creating...' }}' : '{{ isset($editingCategory) ? 'Update Category' : 'Create Category' }}'">
                                    {{ isset($editingCategory) ? 'Update Category' : 'Create Category' }}
                                </span>
                            </button>

                            @if (isset($editingCategory))
                                <a
                                    href="{{ route('admin.categories.index') }}"
                                    class="w-full py-3 rounded-xl border border-sky-950/10 dark:border-[#1e3a5f] text-slate-600 font-black text-[10px] tracking-widest hover:bg-sky-50/50 dark:hover:bg-slate-800 transition-all text-center"
                                >
                                    Cancel Edit
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            {{-- ── Categories list table ── --}}
            <div class="lg:col-span-2">
                <div class="overflow-hidden rounded-[2.5rem] bg-white/50 dark:bg-[#0f172a]/70 backdrop-blur-md border border-sky-950/10 dark:border-[#1e3a5f] shadow-2xl">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-sky-950/10 dark:border-[#1e3a5f]">
                                <th class="px-6 py-4 text-[10px] font-black tracking-widest text-slate-600 dark:text-slate-400">
                                    Category Details
                                </th>
                                <th class="px-6 py-4 text-[10px] font-black tracking-widest text-slate-600 dark:text-slate-400 text-right">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                            @forelse ($categories as $category)
                                <tr class="hover:bg-sky-50/50 dark:hover:bg-[#1e293b]/70 transition-colors">

                                    {{-- Name + slug --}}
                                    <td class="px-6 py-5">
                                        <div class="text-sm font-bold text-slate-900 dark:text-white">
                                            {{ $category->name }}
                                        </div>
                                        <div class="text-[10px] font-mono text-slate-400 tracking-tighter">
                                            Slug: {{ $category->slug }}
                                        </div>
                                    </td>

                                    {{-- Edit / Delete --}}
                                    <td class="px-6 py-5 text-right">
                                        <div class="flex items-center justify-end gap-2">

                                            {{-- Edit: navigate to index with ?edit={id} --}}
                                            <a
                                                href="{{ route('admin.categories.index', ['edit' => $category->id]) }}"
                                                class="p-2 bg-sky-500/10 text-sky-500 hover:bg-sky-500 hover:text-white rounded-lg transition-all"
                                                title="Edit"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>

                                            {{-- Delete --}}
                                            <form
                                                method="POST"
                                                action="{{ route('admin.categories.destroy', $category->id) }}"
                                                x-data
                                                @submit.prevent="
                                                    $dispatch('confirm', {
                                                        type: 'danger',
                                                        title: 'Delete Category',
                                                        confirmText: 'Delete Category',
                                                        message: 'Are you sure you want to delete \'{{ addslashes($category->name) }}\'? This will affect tickets linked to this category.',
                                                        onConfirm: () => $el.submit()
                                                    })
                                                "
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    class="p-2 bg-rose-500/10 text-rose-500 hover:bg-rose-500 hover:text-white rounded-lg transition-all"
                                                    title="Delete"
                                                >
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-6 py-12 text-center">
                                        <p class="text-sm text-slate-600 italic">
                                            No categories found. Create one to get started.
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

</x-app-layout>
