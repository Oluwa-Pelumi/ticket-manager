<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-sky-950 flex items-center justify-center shadow-lg border border-white/20">
                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                </svg>
            </div>
            <div class="flex flex-col">
                <h2 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">Programmes</h2>
                <span class="text-[10px] font-black tracking-[0.3em] text-slate-400">Manage Academic Programmes</span>
            </div>
        </div>
    </x-slot>

    <x-slot name="title">Programmes</x-slot>

    <div class="max-w-7xl mx-auto py-2 px-4 sm:px-6">

        <x-flash-handler />

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">

            {{-- Programme Form --}}
            <div class="lg:col-span-1">
                <div class="fauna-panel p-6 sm:p-8 lg:sticky lg:top-24">
                    <h3 class="text-xl font-black text-slate-900 dark:text-white mb-6 tracking-tight">
                        {{ isset($editingProgramme) ? 'Edit Programme' : 'Add New Programme' }}
                    </h3>

                    <form method="POST"
                        action="{{ isset($editingProgramme) ? route('admin.programmes.update', $editingProgramme->id) : route('admin.programmes.store') }}"
                        class="space-y-6"
                        x-data="{ processing: false }" @submit="processing = true">
                        @csrf
                        @if (isset($editingProgramme)) @method('PATCH') @endif

                        <div class="space-y-2">
                            <label class="text-xs font-black tracking-widest text-slate-600 dark:text-slate-400">Programme Name</label>
                            <input type="text" name="name"
                                value="{{ old('name', $editingProgramme->name ?? '') }}"
                                class="w-full px-4 py-3 rounded-xl bg-white dark:bg-[#1e293b] border border-sky-950/10 dark:border-[#1e3a5f] text-slate-900 dark:text-white focus:ring-2 focus:ring-sky-400 transition-all outline-none font-medium"
                                placeholder="e.g. Computer Science" required />
                            @error('name')<p class="text-rose-500 text-[10px] font-bold">{{ $message }}</p>@enderror
                        </div>

                        <div class="flex flex-col gap-3">
                            <button type="submit" x-bind:disabled="processing"
                                class="fauna-btn-primary w-full !py-4 disabled:opacity-50 flex items-center justify-center gap-2">
                                <template x-if="processing">
                                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                                    </svg>
                                </template>
                                {{ isset($editingProgramme) ? 'Update Programme' : 'Add Programme' }}
                            </button>
                            @if (isset($editingProgramme))
                                <a href="{{ route('admin.programmes.index') }}"
                                    class="w-full py-3 rounded-xl border border-sky-950/10 dark:border-[#1e3a5f] text-slate-600 font-black text-[10px] tracking-widest hover:bg-sky-50/50 dark:hover:bg-slate-800 transition-all text-center">
                                    Cancel Edit
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            {{-- Programmes Table --}}
            <div class="lg:col-span-2">
                <div class="overflow-hidden rounded-[2.5rem] bg-white/50 dark:bg-[#0f172a]/70 backdrop-blur-md border border-sky-950/10 dark:border-[#1e3a5f] shadow-2xl">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-sky-950/10 dark:border-[#1e3a5f]">
                                <th class="px-6 py-4 text-[10px] font-black tracking-widest text-slate-600 dark:text-slate-400">Programme</th>
                                <th class="px-6 py-4 text-[10px] font-black tracking-widest text-slate-600 dark:text-slate-400 text-center">Enrolled Users</th>
                                <th class="px-6 py-4 text-[10px] font-black tracking-widest text-slate-600 dark:text-slate-400 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                            @forelse ($programmes as $programme)
                                <tr class="hover:bg-sky-50/50 dark:hover:bg-[#1e293b]/70 transition-colors">
                                    <td class="px-6 py-5">
                                        <div class="text-sm font-bold text-slate-900 dark:text-white">{{ $programme->name }}</div>
                                        <div class="text-[10px] font-mono text-slate-400 tracking-tighter">{{ $programme->slug }}</div>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black bg-sky-100 dark:bg-sky-900/30 text-sky-700 dark:text-sky-400">
                                            {{ $programme->users_count }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.programmes.index', ['edit' => $programme->id]) }}"
                                                class="p-2 bg-sky-500/10 text-sky-500 hover:bg-sky-500 hover:text-white rounded-lg transition-all" title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                            <form method="POST" action="{{ route('admin.programmes.destroy', $programme->id) }}"
                                                x-data
                                                @submit.prevent="$dispatch('confirm', {
                                                    type: 'danger',
                                                    title: 'Delete Programme',
                                                    confirmText: 'Delete Programme',
                                                    message: 'Delete \'{{ addslashes($programme->name) }}\'?',
                                                    onConfirm: () => $el.submit()
                                                })">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="p-2 bg-rose-500/10 text-rose-500 hover:bg-rose-500 hover:text-white rounded-lg transition-all" title="Delete">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center">
                                        <p class="text-sm text-slate-600 italic">No programmes yet. Add one to get started.</p>
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
