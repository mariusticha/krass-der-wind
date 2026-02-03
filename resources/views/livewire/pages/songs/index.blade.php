<div>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-12 relative z-10">
        <x-page-header title="Songs" :description="auth()->guest() ? 'Browse our song repertoire' : 'Manage your band\'s song collection'">
            <x-slot:actions>
                @auth
                    <flux:button href="{{ route('songs.create') }}" wire:navigate icon="plus">
                        Create Song
                    </flux:button>
                @endauth
            </x-slot:actions>
        </x-page-header>

        @if ($songs->isEmpty())
            <flux:card>
                <p class="text-gray-500 dark:text-gray-400 text-center py-8">No songs yet.</p>
            </flux:card>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach ($songs as $song)
                    <x-song-card :song="$song" />
                @endforeach
            </div>

            <div class="mt-8">
                {{ $songs->links() }}
            </div>
        @endif
    </div>
</div>
