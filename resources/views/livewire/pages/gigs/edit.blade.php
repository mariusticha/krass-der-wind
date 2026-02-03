<div
    class="min-h-screen bg-gradient-to-br from-zinc-50 to-zinc-100 dark:from-zinc-900 dark:to-zinc-800 relative overflow-hidden">
    <x-animated-background />

    <x-navigation />

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-12 relative z-10">
        <x-page-header :title="$gigId ? 'Edit Gig' : 'Create New Gig'" :description="$gigId ? 'Update the gig details' : 'Add a new performance to the schedule'" />

        <flux:card>
            <div class="p-6 sm:p-8">
                <form wire:submit="save" class="space-y-6">
                    <flux:input wire:model="name" label="Gig Name" placeholder="e.g. Stadtfest 2026" required />

                    <flux:textarea wire:model="description" label="Description" placeholder="Event details..."
                        rows="3" />

                    <div class="grid grid-cols-2 gap-4">
                        <flux:input wire:model="date" type="date" label="Date" required />

                        <flux:input wire:model="time" type="time" label="Time" />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <flux:input wire:model="location" label="Location" placeholder="e.g. Biergarten am See"
                            required />

                        <flux:input wire:model="city" label="City" placeholder="e.g. München" required />
                    </div>

                    <div>
                        <flux:label>Setlist</flux:label>
                        <flux:subheading class="mb-3">Manage the songs for this gig</flux:subheading>

                        <flux:checkbox wire:model.live="isOrdered" label="Setlist has a specific order"
                            class="mb-4" />

                        @if (count($selectedSongs) > 0)
                            <div class="space-y-2 mb-4" x-data="{
                                songs: @js($selectedSongs),
                                isOrdered: @entangle('isOrdered').live,
                                init() {
                                    if (this.isOrdered) {
                                        this.initSortable();
                                    }
                                    this.$watch('isOrdered', value => {
                                        if (value) {
                                            this.initSortable();
                                        } else if (this.sortable) {
                                            this.sortable.destroy();
                                            this.sortable = null;
                                        }
                                    });
                                },
                                initSortable() {
                                    if (this.sortable) return;
                                    this.$nextTick(() => {
                                        this.sortable = Sortable.create(this.$refs.songsList, {
                                            animation: 150,
                                            handle: '.drag-handle',
                                            onEnd: () => {
                                                const orderedIds = Array.from(this.$refs.songsList.children).map(el => parseInt(el.dataset.songId));
                                                @this.call('updateSongOrder', orderedIds);
                                            }
                                        });
                                    });
                                }
                            }" x-init="init()">
                                <div x-ref="songsList">
                                    @foreach ($selectedSongs as $index => $song)
                                        <div data-song-id="{{ $song['id'] }}"
                                            class="flex items-start gap-2 p-3 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
                                            @if ($isOrdered)
                                                <div
                                                    class="drag-handle cursor-move flex-shrink-0 mt-2 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300">
                                                    <flux:icon.bars-3 class="size-5" />
                                                </div>
                                            @endif
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-start justify-between gap-2">
                                                    <div class="flex-1 min-w-0">
                                                        <div class="font-medium text-zinc-900 dark:text-zinc-100">
                                                            {{ $song['name'] }}</div>
                                                        <div class="text-sm text-zinc-600 dark:text-zinc-400">
                                                            {{ $song['artist'] }}
                                                            @if ($song['year'])
                                                                <span
                                                                    class="text-zinc-400 dark:text-zinc-500">({{ $song['year'] }})</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <flux:button type="button"
                                                        wire:click="removeSelectedSong({{ $index }})"
                                                        variant="ghost" color="red" icon="trash" size="sm"
                                                        square />
                                                </div>
                                                <flux:input wire:model="selectedSongs.{{ $index }}.notes"
                                                    placeholder="Add notes (e.g., 'extended intro', 'acoustic version')"
                                                    class="mt-2" size="sm" />
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="text-sm text-zinc-500 dark:text-zinc-400 italic mb-4">
                                No songs added yet. Search and add songs below.
                            </div>
                        @endif

                        <div class="space-y-3">
                            <flux:input wire:model.live.debounce.300ms="songSearch"
                                placeholder="Search songs by name or artist..." icon="magnifying-glass" />

                            @if ($songSearch && $this->availableSongs->count() > 0)
                                <div
                                    class="max-h-60 overflow-y-auto space-y-1 border border-zinc-200 dark:border-zinc-700 rounded-lg p-2">
                                    @foreach ($this->availableSongs as $song)
                                        @php
                                            $isAdded = collect($selectedSongs)->contains('id', $song->id);
                                        @endphp
                                        <button type="button" wire:click="addSelectedSong({{ $song->id }})"
                                            @disabled($isAdded)
                                            class="w-full text-left px-3 py-2 rounded hover:bg-zinc-100 dark:hover:bg-zinc-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                                            <div class="font-medium text-sm text-zinc-900 dark:text-zinc-100">
                                                {{ $song->name }}</div>
                                            <div class="text-xs text-zinc-600 dark:text-zinc-400">
                                                {{ $song->artist }}
                                                @if ($song->year)
                                                    <span
                                                        class="text-zinc-400 dark:text-zinc-500">({{ $song->year }})</span>
                                                @endif
                                            </div>
                                        </button>
                                    @endforeach
                                </div>
                            @elseif($songSearch)
                                <div class="text-sm text-zinc-500 dark:text-zinc-400 italic py-2">
                                    No songs found. <button type="button" wire:click="openAddSongModal"
                                        class="text-blue-600 dark:text-blue-400 hover:underline">Create a new
                                        song</button>
                                </div>
                            @endif

                            <flux:button type="button" wire:click="openAddSongModal" variant="ghost" icon="plus"
                                size="sm">
                                Create New Song
                            </flux:button>
                        </div>
                    </div>

                    <flux:checkbox wire:model="isPublic" label="Make this gig public (visible to everyone)" />

                    <div class="flex justify-end gap-3">
                        <flux:button type="button" variant="ghost" wire:click="cancel">Cancel</flux:button>
                        <flux:button type="submit" variant="primary">{{ $gigId ? 'Update' : 'Create' }} Gig
                        </flux:button>
                    </div>
                </form>
            </div>
        </flux:card>
    </div>

    {{-- Add Song Modal --}}
    <flux:modal wire:model="showAddSongModal" class="space-y-6">
        <div>
            <flux:heading size="lg">Create New Song</flux:heading>
            <flux:subheading>Add a new song to the library</flux:subheading>
        </div>

        <flux:input wire:model="newSongName" label="Song Name" placeholder="e.g. Bohemian Rhapsody" required />

        <flux:input wire:model="newSongArtist" label="Artist" placeholder="e.g. Queen" required />

        <flux:input wire:model="newSongYear" type="number" label="Year" placeholder="e.g. 1975" min="1900"
            max="2100" />

        <flux:textarea wire:model="newSongDescription" label="Description"
            placeholder="Additional notes about this song..." rows="3" />

        <div class="flex justify-end gap-3">
            <flux:button wire:click="closeAddSongModal" variant="ghost">Cancel</flux:button>
            <flux:button wire:click="createAndAddSong" variant="primary">Create & Add</flux:button>
        </div>
    </flux:modal>

    @script
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    @endscript
</div>
