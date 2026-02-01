<div class="min-h-screen bg-white dark:bg-zinc-900">
    <x-public-nav />

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-zinc-100">Gigs</h1>
                <p class="text-zinc-600 dark:text-zinc-400 mt-1">
                    @guest
                        Check out where we've been and where we're going!
                    @else
                        Manage your band's performances
                    @endguest
                </p>
            </div>

            @auth
                <flux:button href="{{ route('gigs.create') }}" wire:navigate icon="plus">
                    Create Gig
                </flux:button>
            @endauth
        </div>

        {{-- Upcoming Gigs --}}
        <div class="mb-12">
            <h2 class="text-2xl font-semibold mb-6">Upcoming Gigs</h2>

            @if(count($upcomingGigs) === 0)
                <flux:card>
                    <p class="text-gray-500 dark:text-gray-400 text-center py-8">No upcoming gigs scheduled.</p>
                </flux:card>
            @else
                <div class="space-y-4">
                    @foreach($upcomingGigs as $gig)
                        <flux:card>
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2">
                                        <h3 class="text-xl font-semibold">{{ $gig->name }}</h3>
                                        @auth
                                            @if($gig->is_public)
                                                <flux:badge color="green" size="sm">Public</flux:badge>
                                            @else
                                                <flux:badge color="zinc" size="sm">Private</flux:badge>
                                            @endif

                                            @php
                                                $userGig = $gig->users->firstWhere('id', auth()->id());
                                                $isAttending = $userGig && $userGig->pivot->rsvp_status === 'yes';
                                            @endphp

                                            @if($isAttending)
                                                <flux:badge color="blue" size="sm">Attending</flux:badge>
                                            @endif
                                        @endauth
                                    </div>

                                    <div class="space-y-1 text-gray-600 dark:text-gray-300">
                                        <p class="flex items-center gap-2">
                                            <flux:icon.calendar class="size-4" />
                                            {{ $gig->date->format('l, F j, Y') }}
                                            @if($gig->time)
                                                at {{ $gig->time->format('H:i') }}
                                            @endif
                                        </p>
                                        <p class="flex items-center gap-2">
                                            <flux:icon.map-pin class="size-4" />
                                            {{ $gig->location }}, {{ $gig->city }}
                                        </p>
                                        @auth
                                            @php
                                                $attendeeCount = $gig->users->where('pivot.rsvp_status', 'yes')->count();
                                            @endphp
                                            <button
                                                wire:click="showAttendees({{ $gig->id }})"
                                                class="flex items-center gap-2 text-sm text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 cursor-pointer"
                                            >
                                                <flux:icon.user-group class="size-4" />
                                                {{ $attendeeCount }} {{ Str::plural('person', $attendeeCount) }} attending
                                            </button>
                                        @endauth
                                    </div>

                                    @if($gig->description)
                                        <p class="mt-3 text-gray-700 dark:text-gray-300">{{ $gig->description }}</p>
                                    @endif

                                    @if($gig->playlist)
                                        <div class="mt-4" x-data="{ open: false }">
                                            <button
                                                @click="open = !open"
                                                class="flex items-center gap-1 font-medium text-sm text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100 mb-2"
                                            >
                                                <span>Playlist ({{ count($gig->playlist) }} songs)</span>
                                                <flux:icon.chevron-down class="size-4 transition-transform" ::class="open && 'rotate-180'" />
                                            </button>
                                            <ul x-show="open" x-collapse class="list-disc list-inside text-sm text-gray-600 dark:text-gray-400 space-y-1">
                                                @foreach($gig->playlist as $song)
                                                    <li>{{ $song }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>

                                @auth
                                    <div class="flex gap-2 ml-4">
                                        @php
                                            $userGig = $gig->users->firstWhere('id', auth()->id());
                                            $isAttending = $userGig && $userGig->pivot->rsvp_status === 'yes';
                                        @endphp

                                        <flux:button
                                            wire:click="toggleRsvp({{ $gig->id }})"
                                            size="sm"
                                            variant="ghost"
                                            :color="$isAttending ? 'blue' : 'zinc'"
                                            icon="{{ $isAttending ? 'check' : 'plus' }}"
                                        >
                                            {{ $isAttending ? 'Attending' : 'Attend' }}
                                        </flux:button>
                                        <flux:button
                                            href="{{ route('gigs.edit', $gig) }}"
                                            wire:navigate
                                            size="sm"
                                            variant="ghost"
                                            icon="pencil"
                                        >
                                            Edit
                                        </flux:button>
                                        <flux:button
                                            wire:click="deleteGig({{ $gig->id }})"
                                            wire:confirm="Are you sure you want to delete this gig?"
                                            size="sm"
                                            variant="ghost"
                                            color="red"
                                            icon="trash"
                                        >
                                            Delete
                                        </flux:button>
                                    </div>
                                @endauth
                            </div>
                        </flux:card>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Past Gigs --}}
        <div>
            <h2 class="text-2xl font-semibold mb-6">Past Gigs</h2>

            @if(count($pastGigs) === 0)
                <flux:card>
                    <p class="text-gray-500 dark:text-gray-400 text-center py-8">No past gigs yet.</p>
                </flux:card>
            @else
                <div class="space-y-4">
                    @foreach($pastGigs as $gig)
                        <flux:card>
                            <div class="flex justify-between items-start">
                                <div class="flex-1 opacity-90">
                                    <div class="flex items-center gap-2 mb-2">
                                        <h3 class="text-xl font-semibold">{{ $gig->name }}</h3>
                                        @auth
                                            @if($gig->is_public)
                                                <flux:badge color="green" size="sm">Public</flux:badge>
                                            @else
                                                <flux:badge color="zinc" size="sm">Private</flux:badge>
                                            @endif

                                            @php
                                                $userGig = $gig->users->firstWhere('id', auth()->id());
                                                $didAttend = $userGig && $userGig->pivot->attended;
                                            @endphp

                                            @if($didAttend)
                                                <flux:badge color="purple" size="sm">Attended</flux:badge>
                                            @endif
                                        @endauth
                                    </div>

                                    <div class="space-y-1 text-gray-600 dark:text-gray-300">
                                        <p class="flex items-center gap-2">
                                            <flux:icon.calendar class="size-4" />
                                            {{ $gig->date->format('l, F j, Y') }}
                                            @if($gig->time)
                                                at {{ $gig->time->format('H:i') }}
                                            @endif
                                        </p>
                                        <p class="flex items-center gap-2">
                                            <flux:icon.map-pin class="size-4" />
                                            {{ $gig->location }}, {{ $gig->city }}
                                        </p>
                                        @auth
                                            @php
                                                $participantCount = $gig->users->where('pivot.attended', true)->count();
                                            @endphp
                                            <button
                                                wire:click="showAttendees({{ $gig->id }})"
                                                class="flex items-center gap-2 text-sm text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300 cursor-pointer"
                                            >
                                                <flux:icon.user-group class="size-4" />
                                                {{ $participantCount }} {{ Str::plural('person', $participantCount) }} attended
                                            </button>
                                        @endauth
                                    </div>

                                    @if($gig->description)
                                        <p class="mt-3 text-gray-700 dark:text-gray-300">{{ $gig->description }}</p>
                                    @endif

                                    @if($gig->playlist)
                                        <div class="mt-4" x-data="{ open: false }">
                                            <button
                                                @click="open = !open"
                                                class="flex items-center gap-1 font-medium text-sm text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100 mb-2"
                                            >
                                                <span>Playlist ({{ count($gig->playlist) }} songs)</span>
                                                <flux:icon.chevron-down class="size-4 transition-transform" ::class="open && 'rotate-180'" />
                                            </button>
                                            <ul x-show="open" x-collapse class="list-disc list-inside text-sm text-gray-600 dark:text-gray-400 space-y-1">
                                                @foreach($gig->playlist as $song)
                                                    <li>{{ $song }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>

                                @auth
                                    <div class="flex gap-2 ml-4">
                                        @php
                                            $userGig = $gig->users->firstWhere('id', auth()->id());
                                            $didAttend = $userGig && $userGig->pivot->attended;
                                        @endphp

                                        <flux:button
                                            wire:click="toggleAttendance({{ $gig->id }})"
                                            size="sm"
                                            variant="ghost"
                                            :color="$didAttend ? 'purple' : 'zinc'"
                                            icon="{{ $didAttend ? 'check' : 'plus' }}"
                                        >
                                            {{ $didAttend ? 'Attended' : 'Mark Attended' }}
                                        </flux:button>
                                        <flux:button
                                            href="{{ route('gigs.edit', $gig) }}"
                                            wire:navigate
                                            size="sm"
                                            variant="ghost"
                                            icon="pencil"
                                        >
                                            Edit
                                        </flux:button>
                                        <flux:button
                                            wire:click="deleteGig({{ $gig->id }})"
                                            wire:confirm="Are you sure you want to delete this gig?"
                                            size="sm"
                                            variant="ghost"
                                            color="red"
                                            icon="trash"
                                        >
                                            Delete
                                        </flux:button>
                                    </div>
                                @endauth
                            </div>
                        </flux:card>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Attendees Modal --}}
    @if($showAttendeesModal && $selectedGig)
        <flux:modal wire:model="showAttendeesModal" class="md:w-[600px]" :backdrop-class="'backdrop-blur-sm'">
            <div>
                <flux:heading size="lg">Attendees</flux:heading>
                <flux:subheading class="mb-4">{{ $selectedGig->name }}</flux:subheading>

                @php
                    // For upcoming gigs, show RSVPs
                    if ($selectedGig->date->isFuture()) {
                        $attendees = $selectedGig->users->where('pivot.rsvp_status', 'yes');
                    } else {
                        // For past gigs, show actual attendees
                        $attendees = $selectedGig->users->where('pivot.attended', true);
                    }
                @endphp

                @if($attendees->isEmpty())
                    <p class="text-gray-500 dark:text-gray-300 text-center py-4">No attendees yet</p>
                @else
                    <ul class="space-y-2">
                        @foreach($attendees as $user)
                            <li class="flex items-center justify-between py-2">
                                <span class="text-gray-900 dark:text-white">{{ $user->name }}</span>
                                <span class="text-sm text-gray-600 dark:text-gray-300">{{ $user->instrument }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <flux:button wire:click="closeAttendeesModal" variant="primary" class="mt-6">
                Close
            </flux:button>
        </flux:modal>
    @endif
</div>
