@props(['title', 'description' => null, 'variant' => 'default'])

<div>
    <h2 @class([
        'text-2xl font-semibold mb-6 bg-clip-text text-transparent',
        'bg-gradient-to-r from-amber-500 to-orange-500' => $variant === 'default',
        'bg-gradient-to-r from-purple-500 to-blue-500' => $variant === 'accent',
        'bg-gradient-to-r from-zinc-500 to-stone-500 dark:from-zinc-300 dark:to-stone-300' =>
            $variant === 'decent',
    ])>
        {{ $title }}
    </h2>
    @if ($description)
        <p class="text-sm mt-2 sm:text-base text-zinc-600 dark:text-zinc-400 mt-1">{{ $description }}</p>
    @endif
</div>
