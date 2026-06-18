<x-app-layout>
    <x-slot name="title">
        @yield('title', config('app.name'))
    </x-slot>

    <div class="min-h-screen">
        @hasSection('header')
        <header class="relative z-10 py-3 md:py-10">
            <div class="mx-auto max-w-[98%] xl:max-w-[1700px] px-2 sm:px-4 lg:px-6">
                <div class="fauna-panel relative overflow-hidden p-4 sm:p-6 md:p-10">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-lime-500 to-transparent opacity-40"></div>
                    @yield('header')
                </div>
            </div>
        </header>
        @endif

        <main class="relative z-10 py-6">
            <div class="mx-auto max-w-[98%] xl:max-w-[1700px] px-2 sm:px-4 lg:px-6">
                @yield('content-body')
            </div>
        </main>
    </div>
</x-app-layout>