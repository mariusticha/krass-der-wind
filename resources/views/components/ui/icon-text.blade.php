@props(['icon', 'size' => 'sm', 'variant' => 'default', 'margin' => null])

@php
    // Determine text size classes based on variant
    $textClass = match ($variant) {
        'sm' => 'text-sm',
        'base' => 'text-sm md:text-base',
        default => 'text-sm',
    };

    $marginClass = match ($variant) {
        'sm' => 'mt-1.5',
        'base' => 'mt-1',
        default => 'mt-1.5',
    };

    $marginClass = $margin ?? $marginClass;

    // Build the icon component name
    $iconComponent = 'flux::icon.' . $icon;
@endphp

<p {{ $attributes->merge(['class' => 'flex items-start gap-2']) }}>
    <x-dynamic-component :component="$iconComponent" class="size-4 flex-shrink-0 mt-1" />
    <span class="{{ $textClass }} {{ $marginClass }} leading-tight">{{ $slot }}</span>
</p>
