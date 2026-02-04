@props(['gig', 'type' => 'upcoming'])

@php
    $isUpcoming = $type === 'upcoming';
    $isPast = $type === 'past';

    // Color scheme based on type
    $hoverGradient = $isUpcoming ? 'from-amber-500/5 to-orange-500/5' : 'from-purple-500/5 to-blue-500/5';
    $hoverShadow = $isUpcoming ? 'hover:shadow-amber-500/10' : 'hover:shadow-purple-500/10';

    // User status
    $userGig = auth()->check() ? $gig->users->firstWhere('id', auth()->id()) : null;
    $isAttending = $userGig && $userGig->pivot->rsvp_status === 'yes';
    $didAttend = $userGig && $userGig->pivot->attended;

    // Attendee counts
    $attendeeCount = $isUpcoming
        ? $gig->users->where('pivot.rsvp_status', 'yes')->count()
        : $gig->users->where('pivot.attended', true)->count();

    // Button colors and text
    $participationColor = $isUpcoming ? ($isAttending ? 'blue' : 'zinc') : ($didAttend ? 'purple' : 'zinc');
    $participationIcon = ($isUpcoming && $isAttending) || ($isPast && $didAttend) ? 'x-mark' : 'plus';
    $participationText = $isUpcoming
        ? ($isAttending
            ? 'Decline'
            : 'Attend')
        : ($didAttend
            ? 'Unattended'
            : 'Mark Attended');

    // Attendee link colors
    $attendeeLinkColor = $isUpcoming
        ? 'text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300'
        : 'text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300';
    $attendeeLabel = $isUpcoming ? 'attending' : 'attended';
@endphp

<flux:card
    class="transition-all duration-300 hover:scale-[1.02] hover:shadow-2xl {{ $hoverShadow }} relative overflow-hidden group">
    <!-- Card hover gradient effect -->
    <div
        class="absolute inset-0 bg-gradient-to-r {{ $hoverGradient }} opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
    </div>

    <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-4 relative z-10"
        :class="{ 'opacity-90': {{ $isPast ? 'true' : 'false' }} }">
        <div class="flex-1 min-w-0">
            <div class="flex flex-wrap items-center gap-2 mb-2">
                <h3 class="text-xl font-semibold">{{ $gig->name }}</h3>
                @auth
                    @if ($gig->is_public)
                        <flux:badge color="green" size="sm">Public</flux:badge>
                    @else
                        <flux:badge color="zinc" size="sm">Private</flux:badge>
                    @endif

                    @if ($isUpcoming && $isAttending)
                        <flux:badge color="blue" size="sm">Attending</flux:badge>
                    @elseif ($isPast && $didAttend)
                        <flux:badge color="purple" size="sm">Attended</flux:badge>
                    @endif
                @endauth
            </div>

            <div class="space-y-1 text-gray-600 dark:text-gray-300">
                <x-icon-text icon="calendar">
                    {{ $gig->date->format('l, F j, Y') }}
                    @if ($gig->time)
                        at {{ $gig->time->format('H:i') }}
                    @endif
                </x-icon-text>
                <x-icon-text icon="map-pin">
                    {{ $gig->location }}, {{ $gig->city }}
                </x-icon-text>
                @auth
                    <button wire:click="showAttendees({{ $gig->id }})" class="{{ $attendeeLinkColor }} cursor-pointer">
                        <x-icon-text icon="user-group" margin="mt-1">
                            {{ $attendeeCount }} {{ Str::plural('person', $attendeeCount) }} {{ $attendeeLabel }}
                        </x-icon-text>
                    </button>
                @endauth
            </div>

            @if ($gig->description)
                <p class="mt-3 text-sm md:text-base text-gray-700 dark:text-gray-300">{{ $gig->description }}</p>
            @endif

            @if ($gig->songs && $gig->songs->count() > 0)
                <div class="mt-4" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="flex items-center gap-1 font-medium text-sm text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100 mb-2">
                        <flux:icon.musical-note class="size-4" />
                        <span>Setlist ({{ $gig->songs->count() }}
                            {{ Str::plural('song', $gig->songs->count()) }})</span>
                        <flux:icon.chevron-down class="size-4 transition-transform" ::class="open && 'rotate-180'" />
                    </button>
                    <div x-show="open" x-collapse class="space-y-1.5">
                        @foreach ($gig->songs as $song)
                            <div class="flex items-center gap-3 text-sm">
                                @if ($song->pivot->order)
                                    <span
                                        class="text-sm font-medium text-zinc-500 dark:text-zinc-400 min-w-[1.25rem] text-right flex-shrink-0">{{ $song->pivot->order }}.</span>
                                @else
                                    <span
                                        class="text-zinc-400 dark:text-zinc-500 min-w-[1.25rem] flex-shrink-0">•</span>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <div class="text-gray-700 dark:text-gray-300">
                                        <span class="font-medium font-sans">{{ $song->name }}</span>
                                        <span class="text-gray-500 dark:text-gray-400">{{ $song->artist }}@if ($song->year)
                                                ({{ $song->year }})
                                            @endif
                                        </span>
                                    </div>
                                    @auth
                                        @if ($song->pivot->notes)
                                            <div class="text-xs text-gray-500 dark:text-gray-400 italic mt-0.5">
                                                {{ $song->pivot->notes }}</div>
                                        @endif
                                    @endauth
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        @auth
            <div class="flex flex-col md:flex-row gap-2 md:ml-4 md:flex-shrink-0">
                <div class="flex flex-wrap md:flex-nowrap gap-2">
                    <flux:button wire:click="{{ $isUpcoming ? 'toggleRsvp' : 'toggleAttendance' }}({{ $gig->id }})"
                        size="sm" variant="ghost" :color="$participationColor" icon="{{ $participationIcon }}"
                        class="flex-1 md:flex-initial">
                        <span>{{ $participationText }}</span>
                    </flux:button>
                    <flux:button wire:click="togglePublic({{ $gig->id }})" size="sm" variant="ghost"
                        icon="{{ $gig->is_public ? 'eye-slash' : 'eye' }}"
                        title="{{ $gig->is_public ? 'Make Private' : 'Make Public' }}" class="flex-1 md:flex-initial">
                        <span>{{ $gig->is_public ? 'Unpublish' : 'Publish' }}</span>
                    </flux:button>
                    <flux:dropdown position="bottom" align="end">
                        <flux:button size="sm" variant="ghost" icon="ellipsis-vertical" square
                            class="w-full md:w-auto">
                        </flux:button>

                        <flux:menu>
                            <flux:menu.item :href="route('gigs.edit', $gig)" wire:navigate icon="pencil">
                                Edit
                            </flux:menu.item>
                            <flux:menu.item wire:click="deleteGig({{ $gig->id }})"
                                wire:confirm="Are you sure you want to delete this gig?" icon="trash" variant="danger">
                                Delete
                            </flux:menu.item>
                        </flux:menu>
                    </flux:dropdown>
                </div>
            </div>
        @endauth
    </div>
</flux:card>
