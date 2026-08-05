<<<<<<< HEAD
﻿@props(['disabled' => false, 'error' => false])

@php
    $name = $attributes->get('name');
    $hasError = $error || ($name && isset($errors) && $errors->has($name));
    $borderClasses = $hasError
        ? 'border-red-500 dark:border-red-500 focus:ring-red-500 focus:border-red-500'
        : 'border-slate-200 dark:border-[#1e3a5f] focus:ring-slate-400 focus:border-transparent';
@endphp

<input {{ $disabled ? 'disabled' : '' }} {{ $attributes->merge(['class' => 'w-full px-4 py-3 rounded-xl bg-white dark:bg-[#0f172a] border ' . $borderClasses . ' text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 outline-none transition-all shadow-sm']) }}>
=======
<input {{ $attributes->merge(['class' => 'w-full px-4 py-3 rounded-xl bg-white dark:bg-[#18342f] border border-emerald-900/10 dark:border-[#1d3a34] text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-lime-500 focus:border-transparent outline-none transition-all shadow-sm']) }}>
>>>>>>> parent of bab08b9 (Merge branch 'laradocs' into main)
