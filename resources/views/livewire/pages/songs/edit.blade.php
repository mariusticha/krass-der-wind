<div
    class="min-h-screen bg-gradient-to-br from-zinc-50 to-zinc-100 dark:from-zinc-900 dark:to-zinc-800 relative overflow-hidden">
    <x-layout.animated-background />

    <x-layout.navigation />

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-12 relative z-10">
        <x-ui.page-header :title="$songId ? 'Edit Song' : 'Create New Song'" :description="$songId ? 'Update the song details' : 'Add a new song to the repertoire'" />

        <flux:card>
            <div class="p-6 sm:p-8">
                <form wire:submit="save" class="space-y-6">
                    <flux:input wire:model="name" label="Song Name" placeholder="e.g. Wonderwall" required />

                    <div class="grid grid-cols-2 gap-4">
                        <flux:input wire:model="artist" label="Artist" placeholder="e.g. Oasis" required />

                        <flux:input wire:model="year" type="number" label="Year" placeholder="e.g. 1995"
                            min="1900" max="2100" />
                    </div>

                    <flux:textarea wire:model="description" label="Description (Optional)"
                        placeholder="Additional notes about the song..." rows="3" />

                    <div class="flex justify-between items-center pt-4 border-t border-zinc-200 dark:border-zinc-700">
                        <flux:button type="button" wire:click="cancel" variant="ghost">
                            Cancel
                        </flux:button>

                        <flux:button type="submit" variant="primary">
                            {{ $songId ? 'Update Song' : 'Create Song' }}
                        </flux:button>
                    </div>
                </form>
            </div>
        </flux:card>
    </div>
</div>
