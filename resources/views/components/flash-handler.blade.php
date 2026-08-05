@if(session('success') || session('error') || session('warning') || session('info'))
<div id="flash-toast" class="fixed top-8 right-8 z-[100] flex flex-col gap-3 pointer-events-none" style="min-width:320px;max-width:420px">
    @foreach(['success', 'error', 'warning', 'info'] as $type)
        @if(session($type))
        @php
            $styles = [
                'success' => ['border' => '#10b981', 'icon_color' => '#10b981', 'label' => 'Success',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>'],
                'error'   => ['border' => '#f43f5e', 'icon_color' => '#f43f5e', 'label' => 'Error',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>'],
                'warning' => ['border' => '#f59e0b', 'icon_color' => '#f59e0b', 'label' => 'Warning',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>'],
                'info'    => ['border' => '#3b82f6', 'icon_color' => '#3b82f6', 'label' => 'Info',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20A10 10 0 0012 2z"/>'],
            ];
            $s = $styles[$type];
        @endphp
        <div class="pointer-events-auto flex items-start gap-3 px-4 py-3.5 rounded-2xl shadow-xl bg-white dark:bg-slate-900 border border-black/[0.07] dark:border-white/[0.08]"
             style="border-left: 4px solid {{ $s['border'] }}">
            <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="{{ $s['icon_color'] }}">{!! $s['icon'] !!}</svg>
            <div class="flex-1 flex flex-col gap-0.5">
                <span class="text-[11px] font-black tracking-widest uppercase" style="color: {{ $s['border'] }}">{{ $s['label'] }}</span>
                <p class="text-sm font-medium text-slate-800 dark:text-slate-100 leading-snug">{{ session($type) }}</p>
            </div>
            <button onclick="this.closest('.pointer-events-auto').remove()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors focus:outline-none mt-0.5 shrink-0">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        @endif
    @endforeach
</div>
<script>
    setTimeout(() => document.getElementById('flash-toast')?.remove(), 5000);
</script>
@endif