<?php

use App\Models\Gig;
use Livewire\Component;

new class extends Component
{
    public $upcomingGigs;
    public $pastGigs;

    public function mount()
    {
        $this->loadGigs();
    }

    public function loadGigs()
    {
        $upcomingQuery = Gig::query();
        $pastQuery = Gig::query();

        // Show only public gigs when not authenticated
        if (!auth()->check()) {
            $upcomingQuery->public();
            $pastQuery->public();
        }

        // Load user's RSVP/attendance data if authenticated
        if (auth()->check()) {
            $upcomingQuery->with(['users' => function ($q) {
                $q->where('user_id', auth()->id());
            }]);
            $pastQuery->with(['users' => function ($q) {
                $q->where('user_id', auth()->id());
            }]);
        }

        $this->upcomingGigs = $upcomingQuery->upcoming()->get();
        $this->pastGigs = $pastQuery->past()->get();
    }

    public function deleteGig(Gig $gig): void
    {
        $gig->delete();

        $this->dispatch('gig-deleted');
        $this->loadGigs();
    }
};
?>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gigs - Krass der Wind</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxStyles
</head>
<body class="min-h-screen bg-white dark:bg-zinc-900">
    <x-public-nav />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
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
                <flux:button wire:click="$dispatch('open-gig-form')" icon="plus">
                    Create Gig
                </flux:button>
            @endauth
        </div>

    {{-- Upcoming Gigs --}}
    <div class="mb-12">
        <h2 class="text-2xl font-semibold mb-6">Upcoming Gigs</h2>

        @if(count($upcomingGigs) === 0)
            <flux:card>
                <p class="text-gray-500 text-center py-8">No upcoming gigs scheduled.</p>
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
                                        @if(!$gig->is_public)
                                            <flux:badge color="zinc" size="sm">Private</flux:badge>
                                        @endif
                                    @endauth
                                </div>

                                <div class="space-y-1 text-gray-600">
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
                                </div>

                                @if($gig->description)
                                    <p class="mt-3 text-gray-700">{{ $gig->description }}</p>
                                @endif

                                @if($gig->playlist)
                                    <div class="mt-4">
                                        <p class="font-medium text-sm text-gray-700 mb-2">Playlist:</p>
                                        <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                                            @foreach($gig->playlist as $song)
                                                <li>{{ $song }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @auth
                                    {{-- RSVP Status --}}
                                    <div class="mt-4 pt-4 border-t">
                                        <livewire:gigs.⚡rsvp-button :gig="$gig" :key="'rsvp-'.$gig->id" />
                                    </div>
                                @endauth
                            </div>

                            @auth
                                <div class="flex gap-2 ml-4">
                                    <flux:button
                                        wire:click="$dispatch('edit-gig', { gigId: {{ $gig->id }} })"
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
                <p class="text-gray-500 text-center py-8">No past gigs yet.</p>
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
                                        @if(!$gig->is_public)
                                            <flux:badge color="zinc" size="sm">Private</flux:badge>
                                        @endif
                                    @endauth
                                </div>

                                <div class="space-y-1 text-gray-600">
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
                                </div>

                                @if($gig->description)
                                    <p class="mt-3 text-gray-700">{{ $gig->description }}</p>
                                @endif

                                @if($gig->playlist)
                                    <div class="mt-4">
                                        <p class="font-medium text-sm text-gray-700 mb-2">Playlist:</p>
                                        <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                                            @foreach($gig->playlist as $song)
                                                <li>{{ $song }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @auth
                                    {{-- Attendance Status --}}
                                    <div class="mt-4 pt-4 border-t">
                                        <livewire:gigs.⚡attendance-button :gig="$gig" :key="'attendance-'.$gig->id" />
                                    </div>
                                @endauth
                            </div>

                            @auth
                                <div class="flex gap-2 ml-4">
                                    <flux:button
                                        wire:click="$dispatch('edit-gig', { gigId: {{ $gig->id }} })"
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

    @auth
        {{-- Form Modal --}}
        <livewire:gigs.⚡form />
    @endauth
    </div>

    @fluxScripts
</body>
</html>
