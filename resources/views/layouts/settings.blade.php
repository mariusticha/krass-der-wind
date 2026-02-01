<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data :class="$flux.dark && 'dark'">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Settings' }} - Krass der Wind</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
</head>
<body class="min-h-screen bg-white dark:bg-zinc-900">
    <x-public-nav />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        {{ $slot }}
    </div>

    @fluxScripts
</body>
</html>
