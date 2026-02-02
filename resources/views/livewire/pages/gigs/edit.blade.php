<div class="min-h-screen bg-gradient-to-br from-zinc-50 to-zinc-100 dark:from-zinc-900 dark:to-zinc-800 relative overflow-hidden">
    <x-animated-background />

    <x-public-nav />

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-12 relative z-10">
        <x-page-header
            :title="$gigId ? 'Edit Gig' : 'Create New Gig'"
            :description="$gigId ? 'Update the gig details' : 'Add a new performance to the schedule'"
        />

        <flux:card>
            <div class="p-6 sm:p-8">
                <form wire:submit="save" class="space-y-6">
                <flux:input
                    wire:model="name"
                    label="Gig Name"
                    placeholder="e.g. Stadtfest 2026"
                    required
                />

                <flux:textarea
                    wire:model="description"
                    label="Description"
                    placeholder="Event details..."
                    rows="3"
                />

                <div class="grid grid-cols-2 gap-4">
                    <flux:input
                        wire:model="date"
                        type="date"
                        label="Date"
                        required
                    />

                    <flux:input
                        wire:model="time"
                        type="time"
                        label="Time"
                    />
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <flux:input
                        wire:model="location"
                        label="Location"
                        placeholder="e.g. Biergarten am See"
                        required
                    />

                    <flux:input
                        wire:model="city"
                        label="City"
                        placeholder="e.g. München"
                        required
                    />
                </div>

                <div>
                    <flux:label>Playlist</flux:label>
                    <flux:subheading class="mb-3">Songs to be performed</flux:subheading>

                    <div class="space-y-2">
                        @foreach($playlist as $index => $song)
                            <div class="flex gap-2">
                                <flux:input
                                    wire:model="playlist.{{ $index }}"
                                    placeholder="Song title"
                                    class="flex-1"
                                />
                                <flux:button
                                    type="button"
                                    wire:click="removeSong({{ $index }})"
                                    variant="ghost"
                                    color="red"
                                    icon="trash"
                                    size="sm"
                                >
                                </flux:button>
                            </div>
                        @endforeach
                    </div>

                    <flux:button
                        type="button"
                        wire:click="addSong"
                        variant="ghost"
                        icon="plus"
                        size="sm"
                        class="mt-2"
                    >
                        Add Song
                    </flux:button>
                </div>

                <flux:checkbox wire:model="isPublic" label="Make this gig public (visible to everyone)" />

                <div class="flex justify-end gap-3">
                    <flux:button type="button" variant="ghost" wire:click="cancel">Cancel</flux:button>
                    <flux:button type="submit" variant="primary">{{ $gigId ? 'Update' : 'Create' }} Gig</flux:button>
                </div>
            </form>
            </div>
        </flux:card>
    </div>
</div>
