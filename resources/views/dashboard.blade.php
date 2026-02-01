@php
use App\Models\Gig;

$user = auth()->user();

// Get statistics
$totalGigs = $user->gigs()->count();
$attendedGigs = $user->gigs()->wherePivot('attended', true)->count();
$upcomingRsvps = $user->gigs()
    ->whereHas('users', function($q) use ($user) {
        $q->where('user_id', $user->id)->where('rsvp_status', 'yes');
    })
    ->where('date', '>=', now())
    ->count();

$nextGig = $user->gigs()
    ->whereHas('users', function($q) use ($user) {
        $q->where('user_id', $user->id)->where('rsvp_status', 'yes');
    })
    ->where('date', '>=', now())
    ->orderBy('date')
    ->first();

$lastGig = $user->gigs()
    ->wherePivot('attended', true)
    ->where('date', '<', now())
    ->orderBy('date', 'desc')
    ->first();

$recentGigs = $user->gigs()
    ->where('date', '>=', now())
    ->orderBy('date')
    ->limit(5)
    ->get();
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data :class="$flux.dark && 'dark'">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - Krass der Wind</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
</head>
<body class="min-h-screen bg-white dark:bg-zinc-900 font-serif">
    <x-public-nav />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex h-full w-full flex-1 flex-col gap-6">
        <!-- Welcome Header -->
        <div class="mb-4">
            <h1 class="text-3xl font-bold text-zinc-900 dark:text-zinc-100">Welcome back, {{ $user->name }}!</h1>
            <p class="text-zinc-600 dark:text-zinc-400 mt-1">Here's an overview of your band activity</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <!-- Total Gigs Attended -->
            <flux:card class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400">Total Gigs Attended</p>
                        <p class="text-3xl font-bold text-zinc-900 dark:text-zinc-100 mt-2">{{ $attendedGigs }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-amber-100 dark:bg-amber-900/20 flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-600 dark:text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>
            </flux:card>

            <!-- Upcoming RSVPs -->
            <flux:card class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400">Upcoming Gigs (Accepted)</p>
                        <p class="text-3xl font-bold text-zinc-900 dark:text-zinc-100 mt-2">{{ $upcomingRsvps }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
            </flux:card>

            <!-- Total Gigs -->
            <flux:card class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400">All Gigs</p>
                        <p class="text-3xl font-bold text-zinc-900 dark:text-zinc-100 mt-2">{{ $totalGigs }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-purple-100 dark:bg-purple-900/20 flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
                        </svg>
                    </div>
                </div>
            </flux:card>
        </div>

        <!-- Next and Last Gig -->
        <div class="grid gap-4 md:grid-cols-2">
            @if($nextGig)
                <flux:card class="p-6">
                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4">Next Gig You've Accepted</h3>
                    <div class="space-y-2">
                        <p class="font-medium text-zinc-900 dark:text-zinc-100">{{ $nextGig->name }}</p>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ $nextGig->date->format('l, F j, Y') }}
                            @if($nextGig->time)
                                at {{ $nextGig->time->format('H:i') }}
                            @endif
                        </p>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $nextGig->location }}, {{ $nextGig->city }}
                        </p>
                        <flux:button href="{{ route('gigs.index') }}" wire:navigate class="mt-4" size="sm">
                            View Details
                        </flux:button>
                    </div>
                </flux:card>
            @else
                <flux:card class="p-6">
                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4">Next Gig</h3>
                    <p class="text-zinc-600 dark:text-zinc-400">No upcoming gigs accepted yet.</p>
                    <flux:button href="{{ route('gigs.index') }}" wire:navigate class="mt-4" size="sm">
                        Browse Gigs
                    </flux:button>
                </flux:card>
            @endif

            @if($lastGig)
                <flux:card class="p-6">
                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4">Last Gig Played</h3>
                    <div class="space-y-2">
                        <p class="font-medium text-zinc-900 dark:text-zinc-100">{{ $lastGig->name }}</p>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ $lastGig->date->format('l, F j, Y') }}
                        </p>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $lastGig->location }}, {{ $lastGig->city }}
                        </p>
                        <div class="mt-4 flex items-center gap-2 text-green-600 dark:text-green-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-sm font-medium">Attended</span>
                        </div>
                    </div>
                </flux:card>
            @else
                <flux:card class="p-6">
                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4">Last Gig Played</h3>
                    <p class="text-zinc-600 dark:text-zinc-400">No gigs attended yet.</p>
                </flux:card>
            @endif
        </div>

        <!-- Recent Upcoming Gigs -->
        @if($recentGigs->count() > 0)
            <flux:card class="p-6">
                <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4">Upcoming Gigs</h3>
                <div class="space-y-3">
                    @foreach($recentGigs as $gig)
                        <div class="flex items-center justify-between p-3 rounded-lg bg-zinc-50 dark:bg-zinc-800/50">
                            <div>
                                <p class="font-medium text-zinc-900 dark:text-zinc-100">{{ $gig->name }}</p>
                                <p class="text-sm text-zinc-600 dark:text-zinc-400">
                                    {{ $gig->date->format('M j, Y') }} • {{ $gig->location }}
                                </p>
                            </div>
                            @php
                                $userPivot = $gig->users->where('id', $user->id)->first();
                                $rsvpStatus = $userPivot?->pivot->rsvp_status;
                            @endphp
                            @if($rsvpStatus === 'yes')
                                <flux:badge color="green">Accepted</flux:badge>
                            @elseif($rsvpStatus === 'no')
                                <flux:badge color="red">Declined</flux:badge>
                            @else
                                <flux:badge color="zinc">No Response</flux:badge>
                            @endif
                        </div>
                    @endforeach
                </div>
                <flux:button href="{{ route('gigs.index') }}" wire:navigate class="mt-4 w-full" variant="ghost">
                    View All Gigs
                </flux:button>
            </flux:card>
        @endif
    </div>
    </div>

    @fluxScripts
</body>
</html>
