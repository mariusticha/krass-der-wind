@props(['name', 'heading', 'message', 'wireClick', 'confirmLabel' => 'Delete'])

<flux:modal name="{{ $name }}" class="max-w-sm">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">{{ $heading }}</flux:heading>
            <flux:subheading class="mt-1">{{ $message }}</flux:subheading>
        </div>

        <div class="flex justify-end gap-3">
            <flux:modal.close>
                <flux:button variant="filled">Cancel</flux:button>
            </flux:modal.close>

            <flux:modal.close>
                <flux:button wire:click="{{ $wireClick }}" variant="danger">{{ $confirmLabel }}</flux:button>
            </flux:modal.close>
        </div>
    </div>
</flux:modal>
