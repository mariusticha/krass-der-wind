<div
    class="min-h-screen bg-gradient-to-br from-zinc-50 to-zinc-100 dark:from-zinc-900 dark:to-zinc-800 relative overflow-hidden">
    <x-layout.animated-background />

    <x-layout.navigation />

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-12 relative z-10">
        <x-ui.heading-1 :title="$gigId ? __('Edit Gig') : __('Create New Gig')" :description="$gigId ? __('Update the gig details') : __('Add a new performance to the schedule')" />

        <flux:card>
            <div class="p-6 sm:p-8">
                <form wire:submit="save" class="space-y-6">
                    <flux:input wire:model="name" :label="__('Name')" placeholder="e.g. Stadtfest 2026" required />

                    <flux:textarea wire:model="description" :label="__('Description')"
                        :placeholder="__('Event details...')" rows="3" />

                    <flux:input wire:model="linkUrl" type="url" :label="__('Event Link (optional)')"
                        placeholder="https://..." :description="__('Link to an external event page (e.g. tickets, host website)')" />

                    <div class="grid grid-cols-2 gap-4">
                        <flux:input wire:model="date" type="date" :label="__('Date')" required />

                        <flux:input wire:model="time" type="time" :label="__('Time')" />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <flux:input wire:model="location" :label="__('Location')" placeholder="e.g. Biergarten am See"
                            required />

                        <flux:input wire:model="city" :label="__('City')" placeholder="e.g. München" required />
                    </div>

                    <div>
                        <flux:label>{{ __('Setlist') }}</flux:label>
                        <flux:subheading class="mb-3">{{ __('Manage the songs for this gig') }}</flux:subheading>

                        @if (count($selectedSongs) > 0)
                            <div class="space-y-2 mb-4">
                                @foreach ($selectedSongs as $index => $song)
                                    <x-ui.removable-row wire:key="song-{{ $song['id'] }}"
                                        wireClick="removeSelectedSong({{ $index }})"
                                        modalKey="setlist-song-{{ $song['id'] }}" confirmHeading="Remove from setlist"
                                        confirmMessage="This song will be removed from the setlist.">
                                        <div class="font-medium text-zinc-900 dark:text-zinc-100 font-sans mb-1">
                                            {{ $song['name'] }}
                                        </div>
                                        <div class="text-sm text-zinc-600 dark:text-zinc-400 font-sans mb-3">
                                            {{ $song['artist'] }}
                                            @if ($song['year'])
                                                <span
                                                    class="text-zinc-400 dark:text-zinc-500">({{ $song['year'] }})</span>
                                            @endif
                                        </div>
                                        <flux:input wire:model="selectedSongs.{{ $index }}.notes"
                                            :placeholder="__('Add notes (e.g., \'extended intro\', \'acoustic version\')')"
                                            size="sm" />
                                    </x-ui.removable-row>
                                @endforeach
                            </div>
                        @else
                            <div class="text-sm text-zinc-500 dark:text-zinc-400 italic mb-4">
                                {{ __('No songs added yet. Search and add songs below.') }}
                            </div>
                        @endif

                        <div class="space-y-3">
                            <flux:input wire:model.live.debounce.300ms="songSearch"
                                :placeholder="__('Search songs by name or artist...')" icon="magnifying-glass" />

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
                                            <div class="font-medium text-sm text-zinc-900 dark:text-zinc-100 font-sans">
                                                {{ $song->name }}</div>
                                            <div class="text-xs text-zinc-600 dark:text-zinc-400 font-sans">
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
                                    {{ __('No songs found.') }} <button type="button" wire:click="openAddSongModal"
                                        class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Create a new song') }}</button>
                                </div>
                            @endif

                            <flux:button type="button" wire:click="openAddSongModal" variant="ghost" icon="plus"
                                size="sm">
                                {{ __('Create New Song') }}
                            </flux:button>
                        </div>
                    </div>

                    <flux:checkbox wire:model="isPublic" :label="__('Publish gig')" />

                    <div class="flex justify-end gap-3">
                        <flux:button type="button" variant="ghost" wire:click="cancel">{{ __('Cancel') }}
                        </flux:button>
                        <flux:button type="submit" variant="primary">
                            {{ $gigId ? __('Update Gig') : __('Create Gig') }}
                        </flux:button>
                    </div>
                </form>
            </div>
        </flux:card>
    </div>

    {{-- Add Song Modal --}}
    <flux:modal wire:model="showAddSongModal" class="space-y-6">
        <div>
            <flux:heading size="lg">{{ __('Create New Song') }}</flux:heading>
            <flux:subheading>{{ __('Add a new song to the library') }}</flux:subheading>
        </div>

        <flux:input wire:model="newSongName" :label="__('Song Name')" placeholder="e.g. Bohemian Rhapsody" required />

        <flux:input wire:model="newSongArtist" :label="__('Artist')" placeholder="e.g. Queen" required />

        <flux:input wire:model="newSongYear" type="number" :label="__('Year')" placeholder="e.g. 1975"
            min="1900" max="2100" />

        <flux:textarea wire:model="newSongDescription" :label="__('Description')"
            :placeholder="__('Additional notes about this song...')" rows="3" />

        <div class="flex justify-end gap-3">
            <flux:button wire:click="closeAddSongModal" variant="ghost">{{ __('Cancel') }}</flux:button>
            <flux:button wire:click="createAndAddSong" variant="primary">{{ __('Create & Add') }}</flux:button>
        </div>
    </flux:modal>
</div>
