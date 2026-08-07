@props(['messages' => []])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'mt-1 space-y-1']) }}>
        @foreach ((array) $messages as $message)
            <li class="text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</li>
        @endforeach
    </ul>
@endif
