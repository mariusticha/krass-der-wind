<div>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-12 relative z-10">
        <x-ui.heading-1 :title="__('Gigs')" :description="auth()->guest()
            ? __('Check out where we\'ve been and where we\'re going!')
            : __('Manage your band\'s performances')">
            <x-slot:actions>
                @authverified
                <flux:button href="{{ route('gigs.create') }}" wire:navigate icon="plus">
                    {{ __('Create Gig') }}
                </flux:button>
                @endauthverified
            </x-slot:actions>
            </x-heading-1>

            {{-- Upcoming Gigs --}}
            <div class="mb-12">
                <x-ui.heading-2 :title="__('Upcoming')" />

                @if (count($upcomingGigs) === 0)
                    <flux:card>
                        <p class="text-gray-500 dark:text-gray-400 text-center py-8">
                            {{ __('No upcoming gigs scheduled.') }}</p>
                    </flux:card>
                @else
                    <div class="space-y-4">
                        @foreach ($upcomingGigs as $gig)
                            <x-cards.gig-card :gig="$gig" type="upcoming" />
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Past Gigs --}}
            <div>
                <x-ui.heading-2 :title="__('Past')" variant="accent" />

                @if (count($pastGigs) === 0)
                    <flux:card>
                        <p class="text-gray-500 dark:text-gray-400 text-center py-8">{{ __('No past gigs yet.') }}</p>
                    </flux:card>
                @else
                    <div class="space-y-4">
                        @foreach ($pastGigs as $gig)
                            <x-cards.gig-card :gig="$gig" type="past" />
                        @endforeach
                    </div>
                @endif
            </div>
    </div>

    {{-- Gig View Modal --}}
    <flux:modal wire:model="showViewModal" class="md:w-[700px]">
        @if ($viewingGig)
            @php
                $isViewUpcoming = $viewingGig->date->isFuture();
                $viewUserGig = auth()->check() ? $viewingGig->users->firstWhere('id', auth()->id()) : null;
                $isViewAttending = $viewUserGig && $viewUserGig->pivot->rsvp_status === 'yes';
                $didViewAttend = $viewUserGig && $viewUserGig->pivot->attended;
            @endphp

            {{-- Gradient header --}}
            <div class="relative rounded-xl overflow-hidden mb-6 p-5 {{ $isViewUpcoming ? 'bg-gradient-to-br from-amber-500/10 to-orange-500/10' : 'bg-gradient-to-br from-purple-500/10 to-blue-500/10' }}">
                <div class="flex flex-wrap items-start gap-3">
                    <div class="flex-1 min-w-0">
                        <h2 class="text-2xl font-bold text-zinc-900 dark:text-zinc-50">{{ $viewingGig->name }}</h2>
                        @if ($viewingGig->description)
                            <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">{{ $viewingGig->description }}</p>
                        @endif
                    </div>
                    <div class="flex flex-wrap gap-2 flex-shrink-0">
                        @authverified
                            @if ($viewingGig->is_public)
                                <flux:badge color="green" size="sm">{{ __('Public') }}</flux:badge>
                            @else
                                <flux:badge color="zinc" size="sm">{{ __('Private') }}</flux:badge>
                            @endif
                            @if ($isViewUpcoming && $isViewAttending)
                                <flux:badge color="blue" size="sm">{{ __('Attending') }}</flux:badge>
                            @elseif (!$isViewUpcoming && $didViewAttend)
                                <flux:badge color="purple" size="sm">{{ __('Attended') }}</flux:badge>
                            @endif
                        @endauthverified
                        <flux:badge color="{{ $isViewUpcoming ? 'amber' : 'zinc' }}" size="sm">
                            {{ $isViewUpcoming ? __('Upcoming') : __('Past') }}
                        </flux:badge>
                    </div>
                </div>
            </div>

            {{-- Details --}}
            <div class="space-y-3 mb-6">
                <div class="flex items-center gap-3 text-zinc-700 dark:text-zinc-300">
                    <div class="w-8 h-8 rounded-lg bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center flex-shrink-0">
                        <flux:icon.calendar class="size-4 text-zinc-500" />
                    </div>
                    <div>
                        <p class="font-medium">{{ $viewingGig->date->format('l, F j, Y') }}</p>
                        @if ($viewingGig->time)
                            <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('at :time', ['time' => $viewingGig->time->format('H:i')]) }}</p>
                        @endif
                    </div>
                </div>
                <div class="flex items-center gap-3 text-zinc-700 dark:text-zinc-300">
                    <div class="w-8 h-8 rounded-lg bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center flex-shrink-0">
                        <flux:icon.map-pin class="size-4 text-zinc-500" />
                    </div>
                    <div>
                        <p class="font-medium">{{ $viewingGig->location }}</p>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $viewingGig->city }}</p>
                    </div>
                </div>
                @if ($viewingGig->link_url)
                    <div class="flex items-center gap-3 text-zinc-700 dark:text-zinc-300">
                        <div class="w-8 h-8 rounded-lg bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center flex-shrink-0">
                            <flux:icon.arrow-top-right-on-square class="size-4 text-zinc-500" />
                        </div>
                        <div>
                            <a href="{{ $viewingGig->link_url }}" target="_blank" rel="noopener noreferrer"
                                class="font-medium text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 hover:underline transition-colors">
                                {{ $viewingGig->link_text ?: __('Event Page') }}
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Setlist --}}
            @if ($viewingGig->songs->count() > 0)
                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider mb-3 flex items-center gap-2">
                        <flux:icon.musical-note class="size-4" />
                        {{ __('Setlist') }} ({{ $viewingGig->songs->count() }})
                    </h3>
                    <div class="space-y-1.5">
                        @foreach ($viewingGig->songs as $song)
                            <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                                <span class="text-sm font-medium text-zinc-400 dark:text-zinc-500 w-6 text-right flex-shrink-0">
                                    {{ $song->pivot->order ? $song->pivot->order . '.' : '•' }}
                                </span>
                                <div class="flex-1 min-w-0">
                                    <span class="font-medium text-zinc-900 dark:text-zinc-100 font-sans">{{ $song->name }}</span>
                                    <span class="text-sm text-zinc-500 dark:text-zinc-400 ml-1.5">{{ $song->artist }}@if ($song->year) ({{ $song->year }})@endif</span>
                                    @authverified
                                        @if ($song->pivot->notes)
                                            <p class="text-xs text-zinc-500 dark:text-zinc-400 italic mt-0.5">{{ $song->pivot->notes }}</p>
                                        @endif
                                    @endauthverified
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Attendees (auth+verified only) --}}
            @authverified
                @php
                    $modalAttendees = $isViewUpcoming
                        ? $viewingGig->users->where('pivot.rsvp_status', 'yes')
                        : $viewingGig->users->where('pivot.attended', true);

                    $attendeeLabel = $isViewUpcoming ? __('Attending') : __('Attended');
                @endphp
                <div>
                    <h3 class="text-sm font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider mb-3 flex items-center gap-2">
                        <flux:icon.user-group class="size-4" />
                        {{ $attendeeLabel }} ({{ $modalAttendees->count() }})
                    </h3>
                    @if ($modalAttendees->isEmpty())
                        <p class="text-sm text-zinc-500 dark:text-zinc-400 italic">{{ __('No one yet') }}</p>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            @foreach ($modalAttendees as $attendee)
                                <div class="flex items-center justify-between py-2 px-3 rounded-lg {{ $attendee->id === auth()->id() ? 'bg-amber-50 dark:bg-amber-900/20 ring-1 ring-amber-400/50' : 'bg-zinc-50 dark:bg-zinc-800/50' }}">
                                    <span class="font-medium text-sm {{ $attendee->id === auth()->id() ? 'text-amber-900 dark:text-amber-100' : 'text-zinc-800 dark:text-zinc-200' }}">
                                        {{ $attendee->name }}
                                        @if ($attendee->id === auth()->id())
                                            <span class="text-xs text-amber-600 dark:text-amber-400 ml-1">{{ __('(You)') }}</span>
                                        @endif
                                    </span>
                                    <span class="text-xs text-zinc-500 dark:text-zinc-400">{{ $attendee->instrument }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endauthverified

            <div class="mt-6 flex justify-end gap-3">
                @authverified
                    <flux:button :href="route('gigs.edit', $viewingGig)" wire:navigate variant="filled" icon="pencil">
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
