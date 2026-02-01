<div class="min-h-screen bg-white dark:bg-zinc-900">
    <x-public-nav />

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-zinc-900 dark:text-zinc-100">
                {{ $gigId ? 'Edit Gig' : 'Create New Gig' }}
            </h1>
            <p class="text-zinc-600 dark:text-zinc-400 mt-1">
                {{ $gigId ? 'Update the gig details' : 'Add a new performance to the schedule' }}
            </p>
        </div>

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
