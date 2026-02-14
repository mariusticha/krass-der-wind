<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-12 relative z-10">
    <x-ui.page-header :title="$partId ? 'Edit Part' : 'Create New Part'" :description="$partId ? 'Update the part details' : 'Add a new part to the band'" />

    <flux:card>
        <div class="p-6 sm:p-8">
            <form wire:submit="save" class="space-y-6">
                <flux:input wire:model="name" label="Name" placeholder="e.g. 1. Trumpet" required />

                <div class="flex justify-between items-center pt-4 border-t border-zinc-200 dark:border-zinc-700">
                    <flux:button type="button" wire:click="cancel" variant="ghost">
                        Cancel
                    </flux:button>

                    <flux:button type="submit" variant="primary">
                        {{ $partId ? 'Update Part' : 'Create Part' }}
                    </flux:button>
                </div>
            </form>
        </div>
    </flux:card>
</div>
