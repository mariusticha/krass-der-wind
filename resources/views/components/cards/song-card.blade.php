@props(['song'])

@php
    $deleteMessage =
        $song->gigs_count > 0
            ? __('This song is in :count gig(s). It will be removed from all of them.', ['count' => $song->gigs_count])
            : __('This action cannot be undone.');
@endphp

<div>
    <flux:card
        class="transition-all duration-300 hover:scale-[1.02] hover:shadow-2xl hover:shadow-purple-500/10 relative overflow-hidden group flex flex-col">
        <!-- Card hover gradient effect -->
        <div
            class="absolute inset-0 bg-gradient-to-r from-purple-500/5 to-blue-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
        </div>

        <div class="flex flex-col md:flex-row md:justify-between gap-4 relative z-10 flex-1">
            <div class="flex-1 min-w-0 flex flex-col">
                <div>
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <h3 class="text-xl font-semibold font-sans">{{ $song->name }}</h3>
                    </div>

                    <div class="space-y-1 text-gray-600 dark:text-gray-300">
                        <x-ui.icon-text icon="microphone">
                            {{ $song->artist }}
                            @if ($song->year)
                                ({{ $song->year }})
                            @endif
                            </x-icon-text>
                    </div>

                    @authverified
                    @if ($song->description)
                        <p class="mt-3 text-sm md:text-sm text-gray-700 dark:text-gray-300">{{ $song->description }}</p>
                    @else
                        <p class="mt-3 text-sm md:text-sm italic text-gray-500 dark:text-gray-400">
                            {{ __('No description') }}</p>
                    @endif
                    @endauthverified
                </div>
            </div>

            @authverified
            <div class="flex gap-2 absolute top-3 right-3 md:static md:ml-4 md:flex-shrink-0 z-20">
                <flux:dropdown position="bottom" align="end">
                    <flux:button size="sm" variant="ghost" icon="ellipsis-vertical" square
                        class="w-full md:w-auto" />

                    <flux:menu>
                        <flux:menu.item :href="route('songs.edit', $song)" wire:navigate icon="pencil">
                            {{ __('Edit') }}
                        </flux:menu.item>
                        <flux:menu.item
                            x-on:click="$dispatch('modal-show', { name: 'confirm-delete-song-{{ $song->id }}' })"
                            icon="trash" variant="danger">
                            {{ __('Delete') }}
                        </flux:menu.item>
                    </flux:menu>
                </flux:dropdown>
            </div>
            @endauthverified
        </div>

        @authverified
        <div
            class="relative z-10 pt-4 mt-4 border-t border-zinc-200/50 dark:border-zinc-700/50 flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
            @if ($song->sheets()->count() > 0)
                <div class="flex items-center gap-2">
                    <flux:icon.document-text class="size-4" />
                    <span>{{ trans_choice(':count part|:count parts', $song->sheets()->count(), ['count' => $song->sheets()->count()]) }}</span>
                </div>
            @else
                <p class="italic">{{ __('No sheets available') }}</p>
            @endif
            @if ($song->gigs_count > 0)
                <div class="flex items-center gap-2">
                    <flux:icon.calendar class="size-4" />
                    <span>{{ trans_choice(':count gig|:count gigs', $song->gigs_count, ['count' => $song->gigs_count]) }}</span>
                </div>
            @else
                <div></div>
            @endif
        </div>
        @endauthverified
    </flux:card>

    @authverified
    <x-ui.confirm-modal name="confirm-delete-song-{{ $song->id }}" :heading="__('Delete song')" :message="$deleteMessage"
        wireClick="deleteSong({{ $song->id }})" />
    @endauthverified
</div>
