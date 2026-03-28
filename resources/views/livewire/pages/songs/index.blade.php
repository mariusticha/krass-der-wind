<div>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-12 relative z-10">
        <x-ui.heading-1 :title="__('Songs')" :description="auth()->guest() ? __('Browse our song repertoire') : __('Manage your band\'s song collection')">
            <x-slot:actions>
                @authverified
                <flux:button href="{{ route('songs.create') }}" wire:navigate icon="plus">
                    {{ __('Create Song') }}
                </flux:button>
                @endauthverified
            </x-slot:actions>
            </x-heading-1>

            <x-ui.search-input :placeholder="__('Search songs...')" class="mb-6" />

            @if ($songs->isEmpty())
                <flux:card>
                    <p class="text-gray-500 dark:text-gray-400 text-center py-8">{{ __('No songs yet.') }}</p>
                </flux:card>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($songs as $song)
                        <x-cards.song-card :song="$song" />
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $songs->links() }}
                </div>
            @endif
    </div>

    {{-- Song View Modal --}}
    <flux:modal wire:model="showViewModal" class="md:w-[600px]">
        @if ($viewingRecord)
            {{-- Gradient header --}}
            <div class="relative rounded-xl overflow-hidden mb-6 p-5 bg-gradient-to-br from-purple-500/10 to-blue-500/10">
                <div class="flex flex-wrap items-start gap-3">
                    <div class="flex-1 min-w-0">
                        <h2 class="text-2xl font-bold text-zinc-900 dark:text-zinc-50">{{ $viewingRecord->name }}</h2>
                        <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400 flex items-center gap-1.5">
                            <flux:icon.microphone class="size-4 flex-shrink-0" />
                            {{ $viewingRecord->artist }}
                        </p>
                    </div>
                    @if ($viewingRecord->year)
                        <flux:badge color="zinc" size="sm">{{ $viewingRecord->year }}</flux:badge>
                    @endif
                </div>
            </div>

            {{-- Description --}}
            @if ($viewingRecord->description)
                <p class="mb-6 text-zinc-700 dark:text-zinc-300 text-sm leading-relaxed">{{ $viewingRecord->description }}</p>
            @endif

            {{-- Sheets / Download --}}
            @if ($viewingRecord->sheets->isNotEmpty())
                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider mb-3 flex items-center gap-2">
                        <flux:icon.document-text class="size-4" />
                        {{ __('Sheet Music') }}
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        @foreach ($viewingRecord->sheets as $sheet)
                            <a href="{{ URL::temporarySignedRoute('sheets.file', now()->addHours(1), ['sheet' => $sheet]) }}"
                               target="_blank"
                               class="flex items-center gap-3 p-3 rounded-lg border border-zinc-200 dark:border-zinc-700 hover:border-purple-400 dark:hover:border-purple-500 hover:bg-purple-50 dark:hover:bg-purple-900/10 transition-all group">
                                <div class="w-9 h-9 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center flex-shrink-0 group-hover:bg-purple-200 dark:group-hover:bg-purple-900/50 transition-colors">
                                    <flux:icon.document-arrow-down class="size-5 text-purple-600 dark:text-purple-400" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-sm text-zinc-900 dark:text-zinc-100 truncate">{{ $sheet->part->name }}</p>
                                    <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ __('Download PDF') }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="mb-6 p-4 rounded-lg bg-zinc-50 dark:bg-zinc-800/50 text-center">
                    <p class="text-sm text-zinc-500 dark:text-zinc-400 italic">{{ __('No sheet music available') }}</p>
                </div>
            @endif

            {{-- Stats --}}
            <div class="pt-4 border-t border-zinc-200 dark:border-zinc-700 flex items-center gap-4 text-sm text-zinc-500 dark:text-zinc-400">
                <div class="flex items-center gap-1.5">
                    <flux:icon.calendar class="size-4" />
                    <span>{{ trans_choice(':count gig|:count gigs', $viewingRecord->gigs->count(), ['count' => $viewingRecord->gigs->count()]) }}</span>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                @authverified
                    <flux:button :href="route('songs.edit', $viewingRecord)" wire:navigate variant="filled" icon="pencil">
                        {{ __('Edit') }}
                    </flux:button>
                @endauthverified
                <flux:modal.close>
                    <flux:button>{{ __('Close') }}</flux:button>
                </flux:modal.close>
            </div>
        @endif
    </flux:modal>
</div>
