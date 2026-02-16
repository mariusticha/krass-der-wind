<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-12 relative z-10">
    <x-ui.page-header :title="$sheetId ? 'Edit Sheet' : 'Create New Sheet'" :description="$sheetId ? 'Update the sheet details' : 'Add a new sheet to the band'" />

    <flux:card>
        <div class="p-6 sm:p-8">
            <form wire:submit="save" class="space-y-6">
                <div class="grid grid-cols-2 gap-4">
                    <flux:select wire:model="songId" label="Song">
                        <option value="">{{ $sheetId ? 'Choose song...' : 'Select a song' }}</option>
                        @foreach ($songs as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </flux:select>

                    <flux:select wire:model="partId" label="Part">
                        <option value="">{{ $sheetId ? 'Choose part...' : 'Select a part' }}</option>
                        @foreach ($parts as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </flux:select>

                    <input type="file" wire:model="sheet" accept="application/pdf" class="col-span-2" />

                    @error('sheet')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex justify-between items-center pt-4 border-t border-zinc-200 dark:border-zinc-700">
                    <flux:button type="button" wire:click="cancel" variant="ghost">
                        Cancel
                    </flux:button>

                    <flux:button type="submit" variant="primary">
                        {{ $sheetId ? 'Update Sheet' : 'Create Sheet' }}
                    </flux:button>
                </div>
            </form>
        </div>
    </flux:card>
</div>
