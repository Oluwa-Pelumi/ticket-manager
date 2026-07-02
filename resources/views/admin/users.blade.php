<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-teal-900 flex items-center justify-center shadow-lg border border-white/20">
                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <div class="flex flex-col">
                <h2 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">
                    Users
                </h2>
                <span class="text-[10px] font-black tracking-[0.3em] text-slate-400">User Management</span>
            </div>
        </div>
    </x-slot>

<x-slot name="title">User Management</x-slot>


    <div class="max-w-[98%] xl:max-w-[1700px] mx-auto py-2 px-2 sm:px-4 lg:px-6 overflow-x-hidden">

        <div class="mb-8">
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Staff Management</h1>
            <p class="text-sm text-slate-600 mt-1">Manage user accounts and roles across the platform.</p>
        </div>

        <x-flash-handler />

        {{-- Users table --}}
        <div class="overflow-hidden rounded-[2.5rem] bg-white/50 dark:bg-[#102824]/70 backdrop-blur-md border border-emerald-900/10/50 dark:border-[#1d3a34] shadow-2xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-emerald-900/10 dark:border-[#1d3a34]">
                            <th class="px-4 md:px-6 py-4 text-[10px] font-black tracking-wider text-slate-600 dark:text-slate-400">ID</th>
                            <th class="px-4 md:px-6 py-4 text-[10px] font-black tracking-wider text-slate-600 dark:text-slate-400">User Information</th>
                            <th class="hidden sm:table-cell px-6 py-4 text-[10px] font-black tracking-wider text-slate-600 dark:text-slate-400">Activity</th>
                            <th class="px-4 md:px-6 py-4 text-[10px] font-black tracking-wider text-slate-600 dark:text-slate-400">Role</th>
                            <th class="hidden lg:table-cell px-6 py-4 text-[10px] font-black tracking-wider text-slate-600 dark:text-slate-400">Member Since</th>
                            <th class="px-4 md:px-6 py-4 text-[10px] font-black tracking-wider text-slate-600 dark:text-slate-400 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                        @foreach ($users as $user)
                            <tr class="hover:bg-emerald-50/50 dark:hover:bg-[#18342f]/70 transition-colors">

                                {{-- ID --}}
                                <td class="px-4 md:px-6 py-4 text-xs md:text-sm font-medium text-slate-900 dark:text-white">
                                    #{{ $user->id }}
                                </td>

                                {{-- Name + email --}}
                                <td class="px-4 md:px-6 py-4">
                                    <div class="text-xs md:text-sm font-bold text-slate-900 dark:text-white line-clamp-1">{{ $user->name }}</div>
                                    <div class="text-[10px] md:text-xs text-slate-600 dark:text-slate-400 line-clamp-1">{{ $user->email }}</div>
                                </td>

                                {{-- Ticket count --}}
                                <td class="hidden sm:table-cell px-6 py-4 font-medium">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm text-slate-900 dark:text-white">{{ $user->tickets_count }}</span>
                                        <span class="text-xs text-slate-600 dark:text-slate-400">Tickets</span>
                                    </div>
                                </td>

                                {{-- Role badge --}}
                                <td class="px-4 md:px-6 py-4">
                                    @php
                                        $roleBadge = match($user->role) {
                                            'admin'   => 'bg-lime-500/10 text-teal-900 dark:text-lime-400 ring-4 ring-lime-500/10',
                                            'support' => 'bg-yellow-500/10 text-teal-900 dark:text-lime-400 ring-4 ring-yellow-500/10',
                                            default   => 'bg-slate-100 text-slate-600 dark:bg-[#18342f]/60 dark:text-slate-400',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black tracking-widest {{ $roleBadge }}">
                                        {{ $user->role }}
                                    </span>
                                </td>

                                {{-- Member since --}}
                                <td class="hidden lg:table-cell px-6 py-4 text-xs text-slate-600 dark:text-slate-400">
                                    {{ $user->created_at->toFormattedDateString() }}
                                </td>

                                {{-- Actions: role select + delete --}}
                                <td class="px-4 md:px-6 py-4 text-right">
                                    <div class="flex flex-col items-end gap-1 md:gap-2">
                                        <div class="flex items-center space-x-1 md:space-x-2">

                                            {{-- Role select — submits via its own mini-form --}}
                                            <form
                                                method="POST"
                                                action="{{ route('admin.users.update-role', $user->id) }}"
                                                x-data
                                                id="role-form-{{ $user->id }}"
                                                @submit.prevent="
                                                    $dispatch('confirm', {
                                                        title:       'Update Role',
                                                        confirmText: 'Update Role',
                                                        message:     'Are you sure you want to change {{ addslashes($user->name) }}\'s role to ' + $el.querySelector('select').value + '?',
                                                        onConfirm:   () => $el.submit()
                                                    })
                                                "
                                            >
                                                @csrf
                                                @method('PATCH')
                                                <select
                                                    name="role"
                                                    @change="$el.closest('form').dispatchEvent(new Event('submit'))"
                                                    {{ $user->id === auth()->id() ? 'disabled' : '' }}
                                                    class="text-[10px] md:text-xs font-black bg-white dark:bg-[#18342f] border-emerald-900/10 dark:border-[#28524a] rounded-xl focus:ring-2 focus:ring-lime-500 disabled:opacity-50 transition-all cursor-pointer py-1 md:py-1.5 pl-2 pr-8 md:pl-3 md:pr-10 tracking-widest"
                                                >
                                                    <option value="user"    {{ $user->role === 'user'    ? 'selected' : '' }}>User</option>
                                                    <option value="support" {{ $user->role === 'support' ? 'selected' : '' }}>Support</option>
                                                    <option value="admin"   {{ $user->role === 'admin'   ? 'selected' : '' }}>Admin</option>
                                                </select>
                                            </form>

                                            {{-- Delete --}}
                                            <form
                                                method="POST"
                                                action="{{ route('admin.users.destroy', $user->id) }}"
                                                x-data
                                                @submit.prevent="
                                                    $dispatch('confirm', {
                                                        type:        'danger',
                                                        title:       'Delete User',
                                                        confirmText: 'Delete User',
                                                        message:     'Are you sure you want to delete {{ addslashes($user->name) }}? This action cannot be undone.',
                                                        onConfirm:   () => $el.submit()
                                                    })
                                                "
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    {{ $user->id === auth()->id() ? 'disabled' : '' }}
                                                    class="p-1.5 md:p-2 bg-rose-500/10 text-rose-500 hover:bg-rose-500 hover:text-white rounded-lg transition-all disabled:opacity-50 shadow-sm"
                                                    title="Delete User"
                                                >
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>

                                        @if ($user->id === auth()->id())
                                            <div class="text-[9px] md:text-[10px] text-slate-400 font-black tracking-widest">Self</div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-app-layout>
