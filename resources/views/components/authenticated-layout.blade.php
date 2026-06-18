{{--
    Authenticated layout component.
    Used by views that do: <x-authenticated-layout>
    Delegates to <x-app-layout> which provides the navbar, header, CSS, and scripts stack.
--}}
<x-app-layout>
    {{-- Pass title slot through --}}
    <x-slot name="title">{{ $title ?? config('app.name') }}</x-slot>

    {{-- Pass header slot through (renders in the fauna-panel header band) --}}
    @if(isset($header))
        <x-slot name="header">{{ $header }}</x-slot>
    @endif

    {{-- Main content --}}
    <div class="py-6">
        <div class="mx-auto max-w-[98%] xl:max-w-[1700px] px-2 sm:px-4 lg:px-6">
            {{ $slot }}
        </div>
    </div>
</x-app-layout>
