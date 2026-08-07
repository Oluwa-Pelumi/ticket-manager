@props(['active' => false, 'href' => '#'])

<a
    href="{{ $href }}"
    {{ $attributes->merge([
        'class' => 'inline-flex items-center px-4 py-2 text-[10px] font-black tracking-widest rounded-xl transition-all ' .
            ($active
                ? 'bg-white dark:bg-[#18342f] text-teal-900 dark:text-lime-400 shadow-sm'
                : 'text-slate-600 dark:text-slate-400 hover:text-teal-900 dark:hover:text-lime-400 hover:bg-white/60 dark:hover:bg-[#18342f]/60')
    ]) }}
>{{ $slot }}</a>
