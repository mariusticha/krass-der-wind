<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data :class="$flux.dark && 'dark'">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Krass der Wind' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
</head>

<body class="min-h-screen bg-white dark:bg-zinc-900 flex flex-col" x-data="{ scrolled: false, isWelcomePage: {{ request()->routeIs('home') ? 'true' : 'false' }} }"
    @scroll.window="scrolled = window.scrollY > 100">
    <x-layout.animated-background />

    <x-layout.navigation />

    <main class="flex-1">
        {{ $slot }}
    </main>

    @fluxScripts

    <x-layout.footer />
</body>

</html>
