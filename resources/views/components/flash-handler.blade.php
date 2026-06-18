@if(session('success') || session('error') || session('warning') || session('info'))
<div id="flash-toast" class="fixed bottom-8 right-8 z-[100] flex flex-col gap-4 pointer-events-none">
    @foreach(['success', 'error', 'warning', 'info'] as $type)
        @if(session($type))
        <div class="pointer-events-auto flex items-center gap-4 p-5 rounded-[2rem] border backdrop-blur-2xl shadow-2xl min-w-[320px] max-w-md bg-white/80 dark:bg-slate-900/80 border-emerald-900/10 dark:border-slate-800">
            <div class="flex-1">
                <p class="text-sm font-bold tracking-tight {{ $type === 'error' ? 'text-rose-500' : ($type === 'success' ? 'text-emerald-500' : 'text-slate-900 dark:text-white') }}">
                    {{ session($type) }}
                </p>
            </div>
        </div>
        @endif
    @endforeach
</div>
<script>
    setTimeout(() => document.getElementById('flash-toast')?.remove(), 5000);
</script>
@endif