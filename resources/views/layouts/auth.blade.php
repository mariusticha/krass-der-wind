<div class="min-h-screen bg-gradient-to-br from-zinc-50 to-zinc-100 dark:from-zinc-900 dark:to-zinc-800 relative">
    <x-layout.animated-background />

    <div class="relative z-10">
        <x-layouts::auth.simple :title="$title ?? null">
            {{ $slot }}
        </x-layouts::auth.simple>
    </div>
</div>
