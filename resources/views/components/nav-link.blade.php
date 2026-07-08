@props(['active' => false, 'href' => '#'])

<a
    href="{{ $href }}"
    {{ $attributes->merge([
        'class' => 'inline-flex items-center px-4 py-2 text-[10px] font-black tracking-widest rounded-xl transition-all ' .
            ($active
                ? 'bg-white dark:bg-[#1e293b] text-blue-900 dark:text-sky-400 shadow-sm'
                : 'text-slate-600 dark:text-slate-400 hover:text-blue-900 dark:hover:text-sky-400 hover:bg-white/60 dark:hover:bg-[#1e293b]/60')
    ]) }}
>{{ $slot }}</a>
