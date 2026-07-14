<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-sky-950 flex items-center justify-center shadow-lg border border-white/20">
                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <div class="flex flex-col">
                <h2 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">Faculties & Departments</h2>
                <span class="text-[10px] font-black tracking-[0.3em] text-slate-400">Manage Academic Structure</span>
            </div>
        </div>
    </x-slot>

    <x-slot name="title">Faculties & Departments</x-slot>

    <div class="max-w-7xl mx-auto py-2 px-4 sm:px-6"
        x-data="{
            tab: '{{ isset($editingDepartment) ? 'departments' : 'faculties' }}',
        }">

        <x-flash-handler />

        {{-- Tab Bar --}}
        <div class="flex gap-2 mb-6">
            <button @click="tab = 'faculties'" type="button"
                :class="tab === 'faculties'
                    ? 'bg-sky-950 text-white shadow-lg'
                    : 'bg-white/60 dark:bg-[#0f172a]/60 text-slate-600 dark:text-slate-400 border border-sky-950/10 dark:border-[#1e3a5f] hover:bg-sky-50/50 dark:hover:bg-[#1e293b]/70'"
                class="flex items-center gap-2 px-5 py-2.5 rounded-xl font-black text-xs tracking-widest transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                Faculties
                <span class="ml-1 px-2 py-0.5 rounded-full text-[10px] font-black"
                    :class="tab === 'faculties' ? 'bg-white/20' : 'bg-sky-100 dark:bg-sky-900/40 text-sky-700 dark:text-sky-400'">
                    {{ $faculties->count() }}
                </span>
            </button>

            <button @click="tab = 'departments'" type="button"
                :class="tab === 'departments'
                    ? 'bg-sky-950 text-white shadow-lg'
                    : 'bg-white/60 dark:bg-[#0f172a]/60 text-slate-600 dark:text-slate-400 border border-sky-950/10 dark:border-[#1e3a5f] hover:bg-sky-50/50 dark:hover:bg-[#1e293b]/70'"
                class="flex items-center gap-2 px-5 py-2.5 rounded-xl font-black text-xs tracking-widest transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                </svg>
                Departments
                <span class="ml-1 px-2 py-0.5 rounded-full text-[10px] font-black"
                    :class="tab === 'departments' ? 'bg-white/20' : 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400'">
                    {{ $departments->count() }}
                </span>
            </button>
        </div>

        {{-- ══════════════════════════════ FACULTIES TAB ══════════════════════════════ --}}
        <div x-show="tab === 'faculties'" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">

                {{-- Faculty Form --}}
                <div class="lg:col-span-1">
                    <div class="fauna-panel p-6 sm:p-8 lg:sticky lg:top-24">
                        <h3 class="text-xl font-black text-slate-900 dark:text-white mb-6 tracking-tight">
                            {{ isset($editingFaculty) ? 'Edit Faculty' : 'Add New Faculty' }}
                        </h3>

                        <form method="POST"
                            action="{{ isset($editingFaculty) ? route('admin.faculties.update', $editingFaculty->id) : route('admin.faculties.store') }}"
                            class="space-y-6"
                            x-data="{ processing: false }" @submit="processing = true">
                            @csrf
                            @if (isset($editingFaculty)) @method('PATCH') @endif

                            <div class="space-y-2">
                                <label class="text-xs font-black tracking-widest text-slate-600 dark:text-slate-400">Faculty Name</label>
                                <input type="text" name="name"
                                    value="{{ old('name', $editingFaculty->name ?? '') }}"
                                    class="w-full px-4 py-3 rounded-xl bg-white dark:bg-[#1e293b] border border-sky-950/10 dark:border-[#1e3a5f] text-slate-900 dark:text-white focus:ring-2 focus:ring-sky-400 transition-all outline-none font-medium"
                                    placeholder="e.g. Faculty of Science" required />
                                @error('name')<p class="text-rose-500 text-[10px] font-bold">{{ $message }}</p>@enderror
                            </div>

                            <div class="flex flex-col gap-3">
                                <button type="submit" x-bind:disabled="processing"
                                    class="w-full py-4 rounded-xl bg-sky-950 text-white font-black text-sm tracking-widest shadow-lg hover:bg-sky-800 transition-all disabled:opacity-50 flex items-center justify-center gap-2">
                                    <template x-if="processing">
                                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                                        </svg>
                                    </template>
                                    {{ isset($editingFaculty) ? 'Update Faculty' : 'Add Faculty' }}
                                </button>
                                @if (isset($editingFaculty))
                                    <a href="{{ route('admin.structure.index') }}"
                                        class="w-full py-3 rounded-xl border border-sky-950/10 dark:border-[#1e3a5f] text-slate-600 font-black text-[10px] tracking-widest hover:bg-sky-50/50 dark:hover:bg-slate-800 transition-all text-center">
                                        Cancel Edit
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Faculties Table --}}
                <div class="lg:col-span-2">
                    <div class="overflow-hidden rounded-[2.5rem] bg-white/50 dark:bg-[#0f172a]/70 backdrop-blur-md border border-sky-950/10 dark:border-[#1e3a5f] shadow-2xl">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-sky-950/10 dark:border-[#1e3a5f]">
                                    <th class="px-6 py-4 text-[10px] font-black tracking-widest text-slate-600 dark:text-slate-400">Faculty</th>
                                    <th class="px-6 py-4 text-[10px] font-black tracking-widest text-slate-600 dark:text-slate-400 text-center">Depts</th>
                                    <th class="px-6 py-4 text-[10px] font-black tracking-widest text-slate-600 dark:text-slate-400 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                @forelse ($faculties as $faculty)
                                    <tr class="hover:bg-sky-50/50 dark:hover:bg-[#1e293b]/70 transition-colors">
                                        <td class="px-6 py-5">
                                            <div class="text-sm font-bold text-slate-900 dark:text-white">{{ $faculty->name }}</div>
                                            <div class="text-[10px] font-mono text-slate-400 tracking-tighter">{{ $faculty->slug }}</div>
                                        </td>
                                        <td class="px-6 py-5 text-center">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black bg-sky-100 dark:bg-sky-900/30 text-sky-700 dark:text-sky-400">
                                                {{ $faculty->departments_count }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-5 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('admin.structure.index', ['edit_faculty' => $faculty->id]) }}"
                                                    class="p-2 bg-sky-500/10 text-sky-500 hover:bg-sky-500 hover:text-white rounded-lg transition-all" title="Edit">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                                <form method="POST" action="{{ route('admin.faculties.destroy', $faculty->id) }}"
                                                    x-data
                                                    @submit.prevent="$dispatch('confirm', {
                                                        type: 'danger',
                                                        title: 'Delete Faculty',
                                                        confirmText: 'Delete Faculty',
                                                        message: 'Delete \'{{ addslashes($faculty->name) }}\'? All its departments will also be deleted.',
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
                                            <p class="text-sm text-slate-600 italic">No faculties yet. Add one to get started.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

        {{-- ══════════════════════════════ DEPARTMENTS TAB ══════════════════════════════ --}}
        <div x-show="tab === 'departments'" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">

                {{-- Department Form --}}
                <div class="lg:col-span-1">
                    <div class="fauna-panel p-6 sm:p-8 lg:sticky lg:top-24">
                        <h3 class="text-xl font-black text-slate-900 dark:text-white mb-6 tracking-tight">
                            {{ isset($editingDepartment) ? 'Edit Department' : 'Add New Department' }}
                        </h3>

                        <form method="POST"
                            action="{{ isset($editingDepartment) ? route('admin.departments.update', $editingDepartment->id) : route('admin.departments.store') }}"
                            class="space-y-6"
                            x-data="{ processing: false }" @submit="processing = true">
                            @csrf
                            @if (isset($editingDepartment)) @method('PATCH') @endif

                            <div class="space-y-2">
                                <label class="text-xs font-black tracking-widest text-slate-600 dark:text-slate-400">Faculty</label>
                                <select name="faculty_id" required
                                    class="w-full px-4 py-3 rounded-xl bg-white dark:bg-[#1e293b] border border-sky-950/10 dark:border-[#1e3a5f] text-slate-900 dark:text-white focus:ring-2 focus:ring-sky-400 transition-all outline-none font-medium">
                                    <option value="" disabled selected>Select Faculty</option>
                                    @foreach ($faculties as $faculty)
                                        <option value="{{ $faculty->id }}"
                                            {{ old('faculty_id', $editingDepartment->faculty_id ?? '') == $faculty->id ? 'selected' : '' }}>
                                            {{ $faculty->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('faculty_id')<p class="text-rose-500 text-[10px] font-bold">{{ $message }}</p>@enderror
                            </div>

                            <div class="space-y-2">
                                <label class="text-xs font-black tracking-widest text-slate-600 dark:text-slate-400">Department Name</label>
                                <input type="text" name="name"
                                    value="{{ old('name', $editingDepartment->name ?? '') }}"
                                    class="w-full px-4 py-3 rounded-xl bg-white dark:bg-[#1e293b] border border-sky-950/10 dark:border-[#1e3a5f] text-slate-900 dark:text-white focus:ring-2 focus:ring-sky-400 transition-all outline-none font-medium"
                                    placeholder="e.g. Computer Science" required />
                                @error('name')<p class="text-rose-500 text-[10px] font-bold">{{ $message }}</p>@enderror
                            </div>

                            <div class="flex flex-col gap-3">
                                <button type="submit" x-bind:disabled="processing"
                                    class="w-full py-4 rounded-xl bg-sky-950 text-white font-black text-sm tracking-widest shadow-lg hover:bg-sky-800 transition-all disabled:opacity-50 flex items-center justify-center gap-2">
                                    <template x-if="processing">
                                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                                        </svg>
                                    </template>
                                    {{ isset($editingDepartment) ? 'Update Department' : 'Add Department' }}
                                </button>
                                @if (isset($editingDepartment))
                                    <a href="{{ route('admin.structure.index', ['tab' => 'departments']) }}"
                                        class="w-full py-3 rounded-xl border border-sky-950/10 dark:border-[#1e3a5f] text-slate-600 font-black text-[10px] tracking-widest hover:bg-sky-50/50 dark:hover:bg-slate-800 transition-all text-center">
                                        Cancel Edit
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Departments Table --}}
                <div class="lg:col-span-2">
                    <div class="overflow-hidden rounded-[2.5rem] bg-white/50 dark:bg-[#0f172a]/70 backdrop-blur-md border border-sky-950/10 dark:border-[#1e3a5f] shadow-2xl">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-sky-950/10 dark:border-[#1e3a5f]">
                                    <th class="px-6 py-4 text-[10px] font-black tracking-widest text-slate-600 dark:text-slate-400">Department</th>
                                    <th class="px-6 py-4 text-[10px] font-black tracking-widest text-slate-600 dark:text-slate-400">Faculty</th>
                                    <th class="px-6 py-4 text-[10px] font-black tracking-widest text-slate-600 dark:text-slate-400 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                @forelse ($departments as $dept)
                                    <tr class="hover:bg-sky-50/50 dark:hover:bg-[#1e293b]/70 transition-colors">
                                        <td class="px-6 py-5">
                                            <div class="text-sm font-bold text-slate-900 dark:text-white">{{ $dept->name }}</div>
                                            <div class="text-[10px] font-mono text-slate-400 tracking-tighter">{{ $dept->slug }}</div>
                                        </td>
                                        <td class="px-6 py-5">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400">
                                                {{ $dept->faculty->name ?? '—' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-5 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('admin.structure.index', ['edit_department' => $dept->id]) }}"
                                                    class="p-2 bg-sky-500/10 text-sky-500 hover:bg-sky-500 hover:text-white rounded-lg transition-all" title="Edit">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                                <form method="POST" action="{{ route('admin.departments.destroy', $dept->id) }}"
                                                    x-data
                                                    @submit.prevent="$dispatch('confirm', {
                                                        type: 'danger',
                                                        title: 'Delete Department',
                                                        confirmText: 'Delete Department',
                                                        message: 'Delete \'{{ addslashes($dept->name) }}\'?',
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
                                            <p class="text-sm text-slate-600 italic">No departments yet. Add one to get started.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

    </div>

</x-app-layout>
