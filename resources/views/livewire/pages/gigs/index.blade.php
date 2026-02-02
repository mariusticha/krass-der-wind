<div>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-12 relative z-10">
        <x-page-header title="Gigs" :description="auth()->guest()
            ? 'Check out where we\'ve been and where we\'re going!'
            : 'Manage your band\'s performances'">
            <x-slot:actions>
                @auth
                    <flux:button href="{{ route('gigs.create') }}" wire:navigate icon="plus">
                        Create Gig
                    </flux:button>
                @endauth
            </x-slot:actions>
        </x-page-header>

        {{-- Upcoming Gigs --}}
        <div class="mb-12">
            <h2
                class="text-2xl font-semibold mb-6 bg-gradient-to-r from-amber-500 to-orange-500 bg-clip-text text-transparent">
                Upcoming
            </h2>

            @if (count($upcomingGigs) === 0)
                <flux:card>
                    <p class="text-gray-500 dark:text-gray-400 text-center py-8">No upcoming gigs scheduled.</p>
                </flux:card>
            @else
                <div class="space-y-4">
                    @foreach ($upcomingGigs as $gig)
                        <x-gig-card :gig="$gig" type="upcoming" />
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Past Gigs --}}
        <div>
            <h2
                class="text-2xl font-semibold mb-6 bg-gradient-to-r from-purple-500 to-blue-500 bg-clip-text text-transparent">
                Past
            </h2>

            @if (count($pastGigs) === 0)
                <flux:card>
                    <p class="text-gray-500 dark:text-gray-400 text-center py-8">No past gigs yet.</p>
                </flux:card>
            @else
                <div class="space-y-4">
                    @foreach ($pastGigs as $gig)
                        <x-gig-card :gig="$gig" type="past" />
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Attendees Modal --}}
    @if ($showAttendeesModal && $selectedGig)
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

                @if ($attendees->isEmpty())
                    <p class="text-gray-500 dark:text-gray-300 text-center py-4">No attendees yet</p>
                @else
                    <ul class="space-y-2">
                        @foreach ($attendees as $user)
                            <li
                                class="flex items-center justify-between py-2 px-3 rounded-lg {{ $user->id === auth()->id() ? 'bg-amber-100 dark:bg-amber-900/30 ring-2 ring-amber-500/50' : '' }}">
                                <span
                                    class="font-medium {{ $user->id === auth()->id() ? 'text-amber-900 dark:text-amber-100' : 'text-gray-900 dark:text-white' }}">
                                    {{ $user->name }}
                                    @if ($user->id === auth()->id())
                                        <span class="text-xs text-amber-600 dark:text-amber-400">(You)</span>
                                    @endif
                                </span>
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
