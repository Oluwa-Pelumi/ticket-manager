{{--
    Delete Account section.
    Uses x-teleport to render the modal as a direct child of <body>,
    bypassing the backdrop-filter stacking context in parent glass-card elements.
    Communication between the button scope and the modal scope is done via $dispatch window events.
--}}
<section
    class="space-y-6"
    x-data="{ confirmingUserDeletion: false }"
    @close.window="confirmingUserDeletion = false"
>
    <header>
        <h2 class="text-base font-black tracking-[0.2em] text-rose-500">
            Account Deletion
        </h2>
        <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
            Once your account is purged, all resources and data will be permanently decommissioned.
        </p>
    </header>

    <x-danger-button @click="$dispatch('open-delete-modal')">
        Delete Account
    </x-danger-button>

    {{-- Teleported modal — renders as a direct child of <body> to avoid fixed-position stacking issues --}}
    <template x-teleport="body">
        <div
            x-data="{
                open: {{ $errors->userDeletion->isNotEmpty() ? 'true' : 'false' }},
                processing: false,

                init() {
                    this._openHandler  = () => {
                        this.open = true;
                        this.$nextTick(() => this.$refs.password.focus());
                    };

                    this._closeHandler = () => {
                        this.open       = false;
                        this.processing = false;
                    };

                    window.addEventListener('open-delete-modal', this._openHandler);
                    window.addEventListener('close',             this._closeHandler);
                },
                
                destroy() {
                    window.removeEventListener('open-delete-modal', this._openHandler);
                    window.removeEventListener('close',             this._closeHandler);
                }
            }"
            x-show="open"
            x-cloak
            class="fixed inset-0 z-[9999] overflow-y-auto"
            aria-modal="true"
            role="dialog"
            @keydown.escape.window="open = false; processing = false"
        >
            {{-- Backdrop --}}
            <div
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"
                @click="open = false; processing = false"
            ></div>

            {{-- Panel --}}
            <div class="flex min-h-full items-center justify-center p-4">
                <div
                    class="relative w-full max-w-lg bg-white dark:bg-[#102824] rounded-3xl shadow-2xl border border-slate-100 dark:border-[#1d3a34] overflow-hidden"
                    @click.stop
                >
                    <form
                        method="POST"
                        action="{{ route('profile.destroy') }}"
                        class="p-8"
                        @submit="processing = true"
                    >
                        @csrf
                        @method('delete')

                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 rounded-2xl bg-rose-500/10 flex items-center justify-center border border-rose-500/20">
                                <svg class="w-6 h-6 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <h2 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">
                                Confirm Deletion
                            </h2>
                        </div>

                        <p class="text-sm text-slate-600 dark:text-slate-400 mb-6">
                            Once your account is deleted, all of its resources and data will be permanently deleted.
                            Please enter your password to confirm you would like to permanently delete your account.
                        </p>

                        <div>
                            <label class="sr-only" for="del-password">Your Password</label>
                            <input
                                id="del-password"
                                type="password"
                                name="password"
                                class="mt-1 block w-full px-4 py-3 rounded-2xl bg-slate-50 dark:bg-[#18342f] border border-emerald-900/10 dark:border-[#1d3a34] text-slate-900 dark:text-white focus:ring-2 focus:ring-rose-500 transition-all outline-none"
                                placeholder="Enter your password to confirm"
                                x-ref="password"
                            />
                            @error('password', 'userDeletion')
                                <p class="mt-2 text-sm text-rose-600 dark:text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-6 flex justify-end gap-3">
                            <button
                                type="button"
                                @click="open = false; processing = false"
                                class="inline-flex items-center justify-center rounded-2xl border border-emerald-900/20 dark:border-[#1d3a34] px-5 py-2.5 text-xs font-black tracking-widest text-slate-700 dark:text-slate-300 transition-all hover:bg-slate-50 dark:hover:bg-[#18342f]"
                            >
                                Cancel
                            </button>
                            <button
                                type="submit"
                                x-bind:disabled="processing"
                                class="inline-flex items-center justify-center rounded-2xl bg-rose-600 px-5 py-2.5 text-xs font-black tracking-widest text-white shadow-lg transition-all hover:bg-rose-700 disabled:opacity-60 hover:scale-[1.02] active:scale-[0.98]"
                            >
                                <span x-text="processing ? 'Deleting...' : 'Delete Account'">Delete Account</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>
</section>
