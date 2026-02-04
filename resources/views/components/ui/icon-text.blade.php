@props(['icon', 'size' => 'sm', 'variant' => 'default', 'margin' => null])

@php
    // Determine text size classes based on variant
    $textClass = match ($variant) {
        'sm' => 'text-sm',
        'base' => 'text-sm md:text-base',
        default => 'text-sm',
    };

    // Build the icon component name
    $iconComponent = 'flux::icon.' . $icon;
@endphp

<p {{ $attributes->merge(['class' => 'flex items-start gap-2']) }}>
    <x-dynamic-component :component="$iconComponent" class="size-4 flex-shrink-0 mt-1" />
    <span class="{{ $textClass }} mt-1 leading-tight">{{ $slot }}</span>
</p>
