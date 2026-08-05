<x-guest-layout>
    <x-slot name="title">Register</x-slot>

    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Create Account</h1>
        <p class="text-sm text-slate-600 dark:text-slate-400 mt-2">Join us to start managing your tickets</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5" x-data="{ processing: false }" @submit="processing = true">
        @csrf

        <div class="space-y-2">
            <label for="first_name" class="block text-sm font-black tracking-[0.1em] italic text-slate-700 dark:text-slate-300">First Name</label>
            <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}" required autofocus autocomplete="first_name"
                    class="w-full rounded-full border px-4 py-3 shadow focus:ring-2 outline-none dark:bg-[#1e293b] dark:text-white transition-all @error('first_name') border-red-500 dark:border-red-500 focus:border-red-500 focus:ring-red-500 @else border-slate-200 dark:border-[#1e3a5f] focus:ring-slate-400 @enderror"
                placeholder="John">
            @error('first_name')<p class="text-xs font-bold text-emerald-500 mt-2">{{ $message }}</p>@enderror
        </div>

        <div class="space-y-2">
            <label for="middle_name" class="block text-sm font-black tracking-[0.1em] italic text-slate-700 dark:text-slate-300">Middle Name (Optional)</label>
            <input id="middle_name" type="text" name="middle_name" value="{{ old('middle_name') }}" autocomplete="middle_name"
                    class="w-full rounded-full border px-4 py-3 shadow focus:ring-2 outline-none dark:bg-[#1e293b] dark:text-white transition-all @error('middle_name') border-red-500 dark:border-red-500 focus:border-red-500 focus:ring-red-500 @else border-slate-200 dark:border-[#1e3a5f] focus:ring-slate-400 @enderror"
                placeholder="James (optional)">
            @error('middle_name')<p class="text-xs font-bold text-emerald-500 mt-2">{{ $message }}</p>@enderror
        </div>

        <div class="space-y-2">
            <label for="last_name" class="block text-sm font-black tracking-[0.1em] italic text-slate-700 dark:text-slate-300">Last Name</label>
            <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}" required autofocus autocomplete="last_name"
                    class="w-full rounded-full border px-4 py-3 shadow focus:ring-2 outline-none dark:bg-[#1e293b] dark:text-white transition-all @error('last_name') border-red-500 dark:border-red-500 focus:border-red-500 focus:ring-red-500 @else border-slate-200 dark:border-[#1e3a5f] focus:ring-slate-400 @enderror"
                placeholder="Doe">
            @error('last_name')<p class="text-xs font-bold text-emerald-500 mt-2">{{ $message }}</p>@enderror
        </div>

        <div class="space-y-2">
            <label for="email" class="block text-sm font-black tracking-[0.1em] italic text-slate-700 dark:text-slate-300">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                    class="w-full rounded-full border px-4 py-3 shadow focus:ring-2 outline-none dark:bg-[#1e293b] dark:text-white transition-all @error('email') border-red-500 dark:border-red-500 focus:border-red-500 focus:ring-red-500 @else border-slate-200 dark:border-[#1e3a5f] focus:ring-slate-400 @enderror"
                placeholder="john@example.com">
            @error('email')<p class="text-xs font-bold text-emerald-500 mt-2">{{ $message }}</p>@enderror
        </div>

        <div class="space-y-2">
            <label for="email" class="block text-sm font-black tracking-[0.1em] italic text-slate-700 dark:text-slate-300">Matriculation Number</label>
            <input id="matric_no" type="number" name="matric_no" value="{{ old('matric_no') }}" required autocomplete="username"
                    class="w-full rounded-full border px-4 py-3 shadow focus:ring-2 outline-none dark:bg-[#1e293b] dark:text-white transition-all @error('matric_no') border-red-500 dark:border-red-500 focus:border-red-500 focus:ring-red-500 @else border-slate-200 dark:border-[#1e3a5f] focus:ring-slate-400 @enderror"
                placeholder="192250">
            @error('matric_no')<p class="text-xs font-bold text-emerald-500 mt-2">{{ $message }}</p>@enderror
        </div>

        {{-- Programme Select --}}
        <div class="space-y-2">
            <label for="programme_id" class="block text-sm font-black tracking-[0.1em] italic text-slate-700 dark:text-slate-300">Programme</label>
            <select id="programme_id" name="programme_id" required
                            class="w-full rounded-full border px-4 py-3 shadow focus:ring-2 outline-none dark:bg-[#1e293b] dark:text-white transition-all @error('programme_id') border-red-500 dark:border-red-500 focus:border-red-500 focus:ring-red-500 @else border-slate-200 dark:border-[#1e3a5f] focus:ring-slate-400 @enderror">
                <option value="" disabled {{ old('programme_id') ? '' : 'selected' }}>Select your programmeâ€¦</option>
                @foreach ($programmes as $programme)
                    <option value="{{ $programme->id }}" {{ old('programme_id') == $programme->id ? 'selected' : '' }}>
                        {{ $programme->name }}
                    </option>
                @endforeach
            </select>
            @error('programme_id')<p class="text-xs font-bold text-emerald-500 mt-2">{{ $message }}</p>@enderror
        </div>

        <div class="space-y-2">
            <label for="password" class="block text-sm font-black tracking-widest italic text-slate-700 dark:text-slate-300">Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                    class="w-full rounded-full border px-4 py-3 shadow focus:ring-2 outline-none dark:bg-[#1e293b] dark:text-white transition-all @error('password') border-red-500 dark:border-red-500 focus:border-red-500 focus:ring-red-500 @else border-slate-200 dark:border-[#1e3a5f] focus:ring-slate-400 @enderror"
                placeholder="â€¢â€¢â€¢â€¢â€¢â€¢â€¢â€¢">
            @error('password')<p class="text-xs font-bold text-emerald-500 mt-2">{{ $message }}</p>@enderror
        </div>

        <div class="space-y-2">
            <label for="password_confirmation" class="block text-sm font-black tracking-widest italic text-slate-700 dark:text-slate-300">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                    class="w-full rounded-full border px-4 py-3 shadow focus:ring-2 outline-none dark:bg-[#1e293b] dark:text-white transition-all @error('password_confirmation') border-red-500 dark:border-red-500 focus:border-red-500 focus:ring-red-500 @else border-slate-200 dark:border-[#1e3a5f] focus:ring-slate-400 @enderror"
                placeholder="â€¢â€¢â€¢â€¢â€¢â€¢â€¢â€¢">
            @error('password_confirmation')<p class="text-xs font-bold text-emerald-500 mt-2">{{ $message }}</p>@enderror
        </div>

        <div class="pt-4">
            <button type="submit" x-bind:disabled="processing" class="fauna-btn-primary w-full py-4! text-lg disabled:opacity-50 flex items-center justify-center gap-2">
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
                <a href="{{ route('login') }}" class="font-bold text-emerald-950 dark:text-emerald-400 hover:underline">Sign In</a>
            </p>
        </div>
    </form>
</x-guest-layout>
