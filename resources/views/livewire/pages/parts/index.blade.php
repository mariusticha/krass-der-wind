<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-12 relative z-10">
    <x-ui.page-header title="Parts" description="Manage your band's parts collection">
        <x-slot:actions>
            <flux:button href="{{ route('parts.create') }}" wire:navigate icon="plus">
                Create Part
            </flux:button>
        </x-slot:actions>
    </x-ui.page-header>

    @if ($parts->isEmpty())
        <flux:card>
            <p class="text-gray-500 dark:text-gray-400 text-center py-8">No parts yet.</p>
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
