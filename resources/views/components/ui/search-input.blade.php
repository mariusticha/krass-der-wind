@props(['placeholder' => __('Search...')])

<flux:input
    wire:model.live.debounce.300ms="search"
    :placeholder="$placeholder"
    icon="magnifying-glass"
    clearable
    {{ $attributes }}
/>
