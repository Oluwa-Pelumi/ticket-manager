@props(['active' => false, 'href' => '#'])

<a
    href="{{ $href }}"
    {{ $attributes->merge([
        'class' => 'flex w-full items-center px-4 py-3 text-xs font-bold tracking-widest transition-all border-l-4 ' .
            ($active
<<<<<<< HEAD
                ? 'border-emerald-400 bg-emerald-50 dark:bg-emerald-900/10 text-emerald-950 dark:text-emerald-400'
                : 'border-transparent text-slate-600 dark:text-slate-400 hover:border-emerald-950/30 hover:bg-slate-50 dark:hover:bg-[#1e293b]/50 hover:text-emerald-950 dark:hover:text-emerald-400')
=======
                ? 'border-lime-500 bg-lime-50 dark:bg-lime-900/10 text-teal-900 dark:text-lime-400'
                : 'border-transparent text-slate-600 dark:text-slate-400 hover:border-teal-900/30 hover:bg-slate-50 dark:hover:bg-[#18342f]/50 hover:text-teal-900 dark:hover:text-lime-400')
>>>>>>> parent of bab08b9 (Merge branch 'laradocs' into main)
    ]) }}
>{{ $slot }}</a>
