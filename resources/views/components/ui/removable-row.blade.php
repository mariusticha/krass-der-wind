@props(['wireClick'])

<div class="flex items-start mb-4 py-4 px-5 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
    <div class="flex-1 min-w-0">
        {{ $slot }}
    </div>

    <flux:button type="button" wire:click="{{ $wireClick }}" variant="ghost" color="red" icon="trash" size="sm"
        square />
</div>
