@props(['active' => false, 'href' => '#'])

<a
    href="{{ $href }}"
    {{ $attributes->merge([
        'class' => 'flex w-full items-center px-4 py-3 text-xs font-bold tracking-widest transition-all border-l-4 ' .
            ($active
                ? 'border-sky-400 bg-sky-50 dark:bg-sky-900/10 text-blue-900 dark:text-sky-400'
                : 'border-transparent text-slate-600 dark:text-slate-400 hover:border-blue-900/30 hover:bg-slate-50 dark:hover:bg-[#1e293b]/50 hover:text-blue-900 dark:hover:text-sky-400')
    ]) }}
>{{ $slot }}</a>
