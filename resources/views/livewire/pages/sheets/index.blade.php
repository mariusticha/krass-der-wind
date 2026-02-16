<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-12 relative z-10">
    <x-ui.page-header title="Sheets" description="Manage your band's sheet collection">
        <x-slot:actions>
            <flux:button href="{{ route('sheets.create') }}" wire:navigate icon="plus">
                Create Sheet
            </flux:button>
        </x-slot:actions>
    </x-ui.page-header>

    @if ($sheets->isEmpty())
        <flux:card>
            <p class="text-gray-500 dark:text-gray-400 text-center py-8">No sheets yet.</p>
        </flux:card>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach ($sheets as $sheet)
                <x-cards.sheet-card :sheet="$sheet" />
            @endforeach
        </div>

        <div class="mt-8">
            {{ $sheets->links() }}
        </div>
    @endif
</div>
