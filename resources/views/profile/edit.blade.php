<x-authenticated-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-teal-900 flex items-center justify-center shadow-lg border border-white/20">
                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <div class="flex flex-col">
                <h2 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">
                    Profile
                </h2>
                <span class="text-[9px] font-black tracking-[0.3em] text-slate-400">Personal Management</span>
            </div>
        </div>
    </x-slot>

    <x-slot name="title">Profile</x-slot>

    <div class="py-10 px-4 sm:px-0">
        <div class="mx-auto max-w-3xl space-y-6 sm:px-6 lg:px-8">

            {{-- Update Profile Information --}}
            <div class="glass-card p-6 sm:p-10 border-white/20 dark:border-[#1d3a34]/50">
                @include('profile.partials.update-profile-information-form', [
                    'mustVerifyEmail' => $mustVerifyEmail,
                    'status' => $status ?? null,
                ])
            </div>

            {{-- Update Password --}}
            <div class="glass-card p-6 sm:p-10 border-white/20 dark:border-[#1d3a34]/50">
                @include('profile.partials.update-password-form')
            </div>

            {{-- Delete Account --}}
            <div class="glass-card p-6 sm:p-10 border-rose-200/50 dark:border-rose-900/40">
                @include('profile.partials.delete-user-form')
            </div>

        </div>
    </div>
</x-authenticated-layout>