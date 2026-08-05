@props(['active' => false, 'href' => '#'])

<a
    href="{{ $href }}"
    {{ $attributes->merge([
        'class' => 'flex w-full items-center px-4 py-3 text-xs font-bold tracking-widest transition-all border-l-4 ' .
            ($active
                ? 'border-emerald-400 bg-emerald-50 dark:bg-emerald-900/10 text-emerald-950 dark:text-emerald-400'
                : 'border-transparent text-slate-600 dark:text-slate-400 hover:border-emerald-950/30 hover:bg-slate-50 dark:hover:bg-[#1e293b]/50 hover:text-emerald-950 dark:hover:text-emerald-400')
    ]) }}
>{{ $slot }}</a>
