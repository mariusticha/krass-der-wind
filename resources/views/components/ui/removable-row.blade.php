@props([
    'wireClick',
    'modalKey',
    'confirmHeading' => 'Remove item',
    'confirmMessage' => 'This action cannot be undone.',
])

<div>
    <div class="flex items-start py-4 px-5 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
        <div class="flex-1 min-w-0">
            {{ $slot }}
        </div>

        <flux:modal.trigger name="confirm-remove-{{ $modalKey }}">
            <button type="button"
                class="ml-4 flex-shrink-0 p-1.5 rounded-md text-zinc-400 dark:text-zinc-500
                       hover:bg-red-50 dark:hover:bg-red-950/50 hover:text-red-600 dark:hover:text-red-400
                       transition-colors cursor-pointer">
                <flux:icon.trash class="size-4" />
            </button>
        </flux:modal.trigger>
    </div>

    <x-ui.confirm-modal name="confirm-remove-{{ $modalKey }}" :heading="$confirmHeading" :message="$confirmMessage" :wireClick="$wireClick"
        confirmLabel="Remove" />
</div>
