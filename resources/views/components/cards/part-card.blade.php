@props(['part'])

<flux:card
    class="transition-all duration-300 hover:scale-[1.02] hover:shadow-2xl hover:shadow-purple-500/10 relative overflow-hidden group flex flex-col">
    <!-- Card hover gradient effect -->
    <div
        class="absolute inset-0 bg-gradient-to-r from-purple-500/5 to-blue-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
    </div>

    <div class="flex flex-col md:flex-row md:justify-between gap-4 relative z-10 flex-1">
        <div class="flex-1 min-w-0 flex flex-col">
            <div>
                <!-- title -->
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <h3 class="text-xl font-semibold font-sans">{{ $part->name }}</h3>
                </div>

                <!-- description -->
                {{-- <div class="space-y-1 text-gray-600 dark:text-gray-300">
                    <x-ui.icon-text icon="microphone">
                        {{ $song->artist }}
                        @if ($song->year)
                            ({{ $song->year }})
                        @endif
                        </x-icon-text>
                </div> --}}
            </div>
        </div>

        <div class="flex gap-2 absolute top-3 right-3 md:static md:ml-4 md:flex-shrink-0 z-20">
            <flux:dropdown position="bottom" align="end">
                <flux:button size="sm" variant="ghost" icon="ellipsis-vertical" square class="w-full md:w-auto" />

                <flux:menu>
                    <flux:menu.item :href="route('parts.edit', $part)" wire:navigate icon="pencil">
                        Edit
                    </flux:menu.item>
                    <flux:menu.item wire:click="deletePart({{ $part->id }})"
                        wire:confirm="{{ $part->songs_count > 0 ? 'Warning: This part is used in ' . $part->songs_count . ' ' . Str::plural('song', $part->songs_count) . '. Deleting it will remove it from all those songs. ' : '' }}Are you sure you want to delete this part?"
                        icon="trash" variant="danger">
                        Delete
                    </flux:menu.item>
                </flux:menu>
            </flux:dropdown>
        </div>
    </div>

    @auth
        <div
            class="relative z-10 pt-4 mt-4 border-t border-zinc-200/50 dark:border-zinc-700/50 flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
            <div class="flex items-center gap-2">
                <flux:icon.microphone class="size-4" />
                @if ($part->songs_count > 0)
                    <span>{{ $part->songs_count }} {{ Str::plural('song', $part->songs_count) }}</span>
                @else
                    <span>Not used</span>
                @endif
            </div>
            <p>
                Created {{ $part->created_at->diffForHumans() }}
            </p>
        </div>
    @endauth
</flux:card>
