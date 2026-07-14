@extends('layouts.guest')

@section('title', 'Register')

@section('guest-content')
<div class="text-center mb-8">
    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Create Account</h1>
    <p class="text-sm text-slate-600 dark:text-slate-400 mt-2">Join us to start managing your tickets</p>
</div>

<form method="POST" action="{{ route('register') }}" class="space-y-5" x-data="{ processing: false }" @submit="processing = true">
    @csrf

    <div class="space-y-2">
        <label for="first_name" class="block text-sm font-black tracking-[0.1em] italic text-slate-700 dark:text-slate-300">First Name</label>
        <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}" required autofocus autocomplete="first_name"
            class="w-full px-4 py-3 rounded-xl bg-sky-50/50 dark:bg-[#1e293b]/50 border border-sky-950/10 dark:border-[#1e3a5f] text-slate-900 dark:text-white focus:ring-2 focus:ring-sky-400 outline-none"
            placeholder="John">
        @error('first_name')<p class="text-xs font-bold text-rose-500 mt-2">{{ $message }}</p>@enderror
    </div>

    <div class="space-y-2">
        <label for="middle_name" class="block text-sm font-black tracking-[0.1em] italic text-slate-700 dark:text-slate-300">Middle Name</label>
        <input id="middle_name" type="text" name="middle_name" value="{{ old('middle_name') }}" required autofocus autocomplete="middle_name"
            class="w-full px-4 py-3 rounded-xl bg-sky-50/50 dark:bg-[#1e293b]/50 border border-sky-950/10 dark:border-[#1e3a5f] text-slate-900 dark:text-white focus:ring-2 focus:ring-sky-400 outline-none"
            placeholder="James">
        @error('middle_name')<p class="text-xs font-bold text-rose-500 mt-2">{{ $message }}</p>@enderror
    </div>

    <div class="space-y-2">
        <label for="last_name" class="block text-sm font-black tracking-[0.1em] italic text-slate-700 dark:text-slate-300">Last Name</label>
        <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}" required autofocus autocomplete="last_name"
            class="w-full px-4 py-3 rounded-xl bg-sky-50/50 dark:bg-[#1e293b]/50 border border-sky-950/10 dark:border-[#1e3a5f] text-slate-900 dark:text-white focus:ring-2 focus:ring-sky-400 outline-none"
            placeholder="Doe">
        @error('last_name')<p class="text-xs font-bold text-rose-500 mt-2">{{ $message }}</p>@enderror
    </div>

    <div class="space-y-2">
        <label for="email" class="block text-sm font-black tracking-[0.1em] italic text-slate-700 dark:text-slate-300">Email Address</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
            class="w-full px-4 py-3 rounded-xl bg-sky-50/50 dark:bg-[#1e293b]/50 border border-sky-950/10 dark:border-[#1e3a5f] text-slate-900 dark:text-white focus:ring-2 focus:ring-sky-400 outline-none"
            placeholder="john@example.com">
        @error('email')<p class="text-xs font-bold text-rose-500 mt-2">{{ $message }}</p>@enderror
    </div>

    <div class="space-y-2">
        <label for="email" class="block text-sm font-black tracking-[0.1em] italic text-slate-700 dark:text-slate-300">Matriculation Number</label>
        <input id="matric_no" type="number" name="matric_no" value="{{ old('matric_no') }}" required autocomplete="username"
            class="w-full px-4 py-3 rounded-xl bg-sky-50/50 dark:bg-[#1e293b]/50 border border-sky-950/10 dark:border-[#1e3a5f] text-slate-900 dark:text-white focus:ring-2 focus:ring-sky-400 outline-none"
            placeholder="192250">
        @error('matric_no')<p class="text-xs font-bold text-rose-500 mt-2">{{ $message }}</p>@enderror
    </div>

    {{-- Faculty + Department (Alpine.js dynamic selects) --}}
    <div x-data="{
        faculties: @js($faculties),
        departments: [],
        faculty_id: '{{ old('faculty_id') }}',
        department_id: '{{ old('department_id') }}',
        loading: false,
        async fetchDepartments(facultyId) {
            if (!facultyId) { this.departments = []; this.department_id = ''; return; }
            this.loading = true;
            try {
                const res = await fetch(`/api/faculties/${facultyId}/departments`);
                this.departments = await res.json();
                // Restore old value if valid after re-fetch
                if (!this.departments.find(d => d.id == this.department_id)) {
                    this.department_id = '';
                }
            } finally {
                this.loading = false;
            }
        },
        init() {
            if (this.faculty_id) this.fetchDepartments(this.faculty_id);
        }
    }" class="space-y-5">

        {{-- Faculty Select --}}
        <div class="space-y-2">
            <label for="faculty_id" class="block text-sm font-black tracking-[0.1em] italic text-slate-700 dark:text-slate-300">Faculty</label>
            <select id="faculty_id" name="faculty_id" required
                x-model="faculty_id"
                @change="fetchDepartments($event.target.value)"
                class="w-full px-4 py-3 rounded-xl bg-sky-50/50 dark:bg-[#1e293b]/50 border border-sky-950/10 dark:border-[#1e3a5f] text-slate-900 dark:text-white focus:ring-2 focus:ring-sky-400 outline-none">
                <option value="" disabled selected>Select your faculty…</option>
                <template x-for="f in faculties" :key="f.id">
                    <option :value="f.id" x-text="f.name" :selected="f.id == faculty_id"></option>
                </template>
            </select>
            @error('faculty_id')<p class="text-xs font-bold text-rose-500 mt-2">{{ $message }}</p>@enderror
        </div>

        {{-- Department Select (dynamically populated) --}}
        <div class="space-y-2">
            <label for="department_id" class="block text-sm font-black tracking-[0.1em] italic text-slate-700 dark:text-slate-300">Department</label>
            <div class="relative">
                <select id="department_id" name="department_id" required
                    x-model="department_id"
                    :disabled="!faculty_id || loading"
                    class="w-full px-4 py-3 rounded-xl bg-sky-50/50 dark:bg-[#1e293b]/50 border border-sky-950/10 dark:border-[#1e3a5f] text-slate-900 dark:text-white focus:ring-2 focus:ring-sky-400 outline-none disabled:opacity-50 disabled:cursor-not-allowed transition-opacity">
                    <option value="" disabled selected x-text="loading ? 'Loading departments…' : (faculty_id ? 'Select your department…' : 'Select a faculty first')"></option>
                    <template x-for="d in departments" :key="d.id">
                        <option :value="d.id" x-text="d.name" :selected="d.id == department_id"></option>
                    </template>
                </select>
                <template x-if="loading">
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none">
                        <svg class="w-4 h-4 animate-spin text-sky-500" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                        </svg>
                    </div>
                </template>
            </div>
            @error('department_id')<p class="text-xs font-bold text-rose-500 mt-2">{{ $message }}</p>@enderror
        </div>

    </div>

    <div class="space-y-2">
        <label for="password" class="block text-sm font-black tracking-[0.1em] italic text-slate-700 dark:text-slate-300">Password</label>
        <input id="password" type="password" name="password" required autocomplete="new-password"
            class="w-full px-4 py-3 rounded-xl bg-sky-50/50 dark:bg-[#1e293b]/50 border border-sky-950/10 dark:border-[#1e3a5f] text-slate-900 dark:text-white focus:ring-2 focus:ring-sky-400 outline-none"
            placeholder="••••••••">
        @error('password')<p class="text-xs font-bold text-rose-500 mt-2">{{ $message }}</p>@enderror
    </div>

    <div class="space-y-2">
        <label for="password_confirmation" class="block text-sm font-black tracking-[0.1em] italic text-slate-700 dark:text-slate-300">Confirm Password</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
            class="w-full px-4 py-3 rounded-xl bg-sky-50/50 dark:bg-[#1e293b]/50 border border-sky-950/10 dark:border-[#1e3a5f] text-slate-900 dark:text-white focus:ring-2 focus:ring-sky-400 outline-none"
            placeholder="••••••••">
        @error('password_confirmation')<p class="text-xs font-bold text-rose-500 mt-2">{{ $message }}</p>@enderror
    </div>

    <div class="pt-4">
        <button type="submit" x-bind:disabled="processing" class="w-full py-4 rounded-2xl bg-sky-950 text-white font-black text-lg shadow-xl hover:bg-sky-800 hover:text-white transition-all tracking-widest disabled:opacity-50 flex items-center justify-center gap-2">
            <template x-if="processing">
                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                </svg>
            </template>
            <span x-text="processing ? 'Creating Account...' : 'Create Account'">Create Account</span>
        </button>
    </div>

    <div class="text-center pt-4">
        <p class="text-sm text-slate-600 dark:text-slate-400">
            Already have an account?
            <a href="{{ route('login') }}" class="font-bold text-sky-950 dark:text-sky-400 hover:underline">Sign In</a>
        </p>
    </div>
</form>
@endsection