<div>
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-12 relative z-10">
    <x-ui.heading-1 :title="__('Parts')" :description="__('Manage your band\'s parts collection')">
        <x-slot:actions>
            <flux:button href="{{ route('parts.create') }}" wire:navigate icon="plus">
                Create Part
            </flux:button>
        </x-slot:actions>
    </x-ui.heading-1>

    <x-ui.search-input :placeholder="__('Search parts...')" class="mb-6" />

    @if ($parts->isEmpty())
        <flux:card>
            <p class="text-gray-500 dark:text-gray-400 text-center py-8">{{ __('No parts yet.') }}</p>
        </flux:card>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach ($parts as $part)
                <x-cards.part-card :part="$part" />
            @endforeach
        </div>

        <div class="mt-8">
            {{ $parts->links() }}
        </div>
    @endif
</div>

{{-- Part View Modal --}}
<flux:modal wire:model="showViewModal" class="md:w-[520px]">
    @if ($viewingRecord)
        {{-- Gradient header --}}
        <div class="relative rounded-xl overflow-hidden mb-6 p-5 bg-gradient-to-br from-purple-500/10 to-blue-500/10">
            <h2 class="text-2xl font-bold text-zinc-900 dark:text-zinc-50">{{ $viewingRecord->name }}</h2>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ __('Instrument Part') }}</p>
        </div>

        {{-- Songs using this part --}}
        @if ($viewingRecord->songs->isNotEmpty())
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider mb-3 flex items-center gap-2">
                    <flux:icon.musical-note class="size-4" />
                    {{ __('Used in Songs') }} ({{ $viewingRecord->songs->count() }})
                </h3>
                <div class="space-y-1.5">
                    @foreach ($viewingRecord->songs as $song)
                        <a href="{{ route('songs.index', ['view' => $song->id]) }}"
                           wire:navigate
                           class="flex items-center gap-3 p-3 rounded-lg border border-zinc-200 dark:border-zinc-700 hover:border-purple-400 dark:hover:border-purple-500 hover:bg-purple-50 dark:hover:bg-purple-900/10 transition-all group">
                            <div class="w-8 h-8 rounded-lg bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center flex-shrink-0 group-hover:bg-purple-100 dark:group-hover:bg-purple-900/30 transition-colors">
                                <flux:icon.musical-note class="size-4 text-zinc-500 dark:text-zinc-400 group-hover:text-purple-600 dark:group-hover:text-purple-400" />
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-sm text-zinc-900 dark:text-zinc-100">{{ $song->name }}</p>
                                <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ $song->artist }}</p>
                            </div>
                            <flux:icon.arrow-top-right-on-square class="size-4 text-zinc-400 group-hover:text-purple-500 flex-shrink-0" />
                        </a>
                    @endforeach
                </div>
            </div>
        @else
            <div class="mb-6 p-4 rounded-lg bg-zinc-50 dark:bg-zinc-800/50 text-center">
                <p class="text-sm text-zinc-500 dark:text-zinc-400 italic">{{ __('This part is not used in any songs yet') }}</p>
            </div>
        @endif

        <div class="mt-6 flex justify-end gap-3">
            <flux:button :href="route('parts.edit', $viewingRecord)" wire:navigate variant="filled" icon="pencil">
                {{ __('Edit') }}
            </flux:button>
            <flux:modal.close>
                <flux:button>{{ __('Close') }}</flux:button>
            </flux:modal.close>
        </div>
    @endif
</flux:modal>
</div>
