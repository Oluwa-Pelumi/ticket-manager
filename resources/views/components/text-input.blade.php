@props(['disabled' => false, 'error' => false])

@php
    $name = $attributes->get('name');
    $hasError = $error || ($name && isset($errors) && $errors->has($name));
    $borderClasses = $hasError
        ? 'border-rose-500 dark:border-rose-500 focus:ring-rose-500 focus:border-rose-500'
        : 'border-slate-200 dark:border-[#1e3a5f] focus:ring-slate-400 focus:border-transparent';
@endphp

<input {{ $disabled ? 'disabled' : '' }} {{ $attributes->merge(['class' => 'w-full px-4 py-3 rounded-xl bg-white dark:bg-[#0f172a] border ' . $borderClasses . ' text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 outline-none transition-all shadow-sm']) }}>
