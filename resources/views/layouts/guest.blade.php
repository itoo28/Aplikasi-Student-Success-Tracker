<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Student Success Tracker') }}</title>
        <link rel="icon" type="image/svg+xml" href="{{ asset('brand/logo-uhb.svg') }}">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @fluxAppearance
        @livewireStyles
    </head>
    <body class="min-h-screen bg-zinc-50 antialiased">
        <flux:accent color="indigo">
            <div class="relative min-h-screen overflow-hidden">
                <div class="pointer-events-none absolute inset-0">
                    <div class="absolute -left-20 top-12 size-80 rounded-full bg-indigo-200/40 blur-3xl"></div>
                    <div class="absolute -right-20 bottom-8 size-96 rounded-full bg-sky-200/40 blur-3xl"></div>
                </div>

                <div class="relative">
                    {{ $slot }}
                </div>
            </div>
        </flux:accent>

        @livewireScripts
        @fluxScripts
        @stack('scripts')
    </body>
</html>
