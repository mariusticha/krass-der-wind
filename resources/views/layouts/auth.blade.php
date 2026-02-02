<x-layouts::auth.simple :title="$title ?? null">
    <div class="relative">
        <x-animated-background />
        {{ $slot }}
    </div>
</x-layouts::auth.simple>
