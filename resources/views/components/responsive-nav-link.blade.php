@props(['active' => false, 'href' => '#'])

<a
    href="{{ $href }}"
    {{ $attributes->merge([
        'class' => 'flex w-full items-center px-4 py-3 text-xs font-bold tracking-widest transition-all border-l-4 ' .
            ($active
                ? 'border-rose-400 bg-rose-50 dark:bg-rose-900/10 text-rose-950 dark:text-rose-400'
                : 'border-transparent text-slate-600 dark:text-slate-400 hover:border-rose-950/30 hover:bg-slate-50 dark:hover:bg-[#1e293b]/50 hover:text-rose-950 dark:hover:text-rose-400')
    ]) }}
>{{ $slot }}</a>
