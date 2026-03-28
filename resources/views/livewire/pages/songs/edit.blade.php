<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-12 relative z-10">
    <x-ui.heading-1 :title="$songId ? 'Edit Song' : 'Create New Song'" :description="$songId ? 'Update the song details' : 'Add a new song to the repertoire'" />

    <flux:card>
        <div class="p-6 sm:p-8">
            <form wire:submit="save" class="space-y-6">
                <flux:input wire:model="name" label="Name" placeholder="e.g. Wonderwall" required />

                <div class="grid grid-cols-2 gap-4">
                    <flux:input wire:model="artist" label="Artist" placeholder="e.g. Oasis" required />

                    <flux:input wire:model="year" type="number" label="Year" placeholder="e.g. 1995" min="1900"
                        max="2100" />
                </div>

                <flux:textarea wire:model="description" label="Description (Optional)"
                    placeholder="Additional notes about the song..." rows="3" />

                <div class="flex justify-between items-center pt-4 border-t border-zinc-200 dark:border-zinc-700">
                    <flux:button type="button" wire:click="cancel" variant="ghost">
                        Cancel
                    </flux:button>

                    <flux:button type="submit" variant="primary">
                        {{ $songId ? 'Update Song' : 'Create Song' }}
                    </flux:button>
                </div>
            </form>
        </div>
    </flux:card>

    @if ($songId)
        <div class="mt-8">
            <flux:card>
                <div class="p-6 sm:p-8">
                    <flux:label>Sheets</flux:label>
                    <flux:subheading class="mb-4">Manage the music sheets for this song, one per instrument part
                    </flux:subheading>

                    @if ($sheets->isEmpty())
                        <p class="text-sm text-zinc-500 dark:text-zinc-400 italic mb-6">
                            No sheets yet. Upload the first one below.
                        </p>
                    @else
                        <div class="space-y-2 mb-6">
                            @foreach ($sheets as $sheet)
                                <x-ui.removable-row wire:key="sheet-{{ $sheet->id }}"
                                    wireClick="removeSheet({{ $sheet->id }})" modalKey="sheet-{{ $sheet->id }}"
                                    confirmHeading="Remove sheet"
                                    confirmMessage="The PDF file will be permanently deleted.">
                                    <div class="flex items-center gap-3">
                                        <flux:icon.document-text class="size-5 text-zinc-400 flex-shrink-0" />
                                        <div>
                                            <div class="font-medium text-zinc-900 dark:text-zinc-100 font-sans">
                                                {{ $sheet->part->name }}
                                            </div>
                                            <a href="{{ URL::temporarySignedRoute('sheets.file', now()->addHours(1), ['sheet' => $sheet]) }}"
                                                target="_blank"
                                                class="text-sm text-amber-600 dark:text-amber-400 hover:underline flex items-center gap-1">
                                                <flux:icon.arrow-down-tray class="size-3.5" />
                                                View / Download PDF
                                            </a>
                                        </div>
                                    </div>
                                </x-ui.removable-row>
                            @endforeach
                        </div>
                    @endif

                    <div class="space-y-4 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                        <flux:subheading>Add a sheet</flux:subheading>

                        <div class="grid grid-cols-2 gap-4">
                            <flux:field>
                                <flux:label>Part</flux:label>
                                <flux:select wire:model="newSheetPartId" placeholder="Select a part...">
                                    @foreach ($parts as $id => $name)
                                        <flux:select.option value="{{ $id }}">{{ $name }}
                                        </flux:select.option>
                                    @endforeach
                                </flux:select>
                                <flux:error name="newSheetPartId" />
                            </flux:field>

                            <flux:field>
                                <flux:label>PDF File</flux:label>
                                <input type="file" wire:model="newSheetFile" accept="application/pdf"
                                    class="block w-full text-sm text-zinc-600 dark:text-zinc-400
                                           file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0
                                           file:text-sm file:font-medium
                                           file:bg-zinc-100 file:text-zinc-700
                                           dark:file:bg-zinc-700 dark:file:text-zinc-200
                                           hover:file:bg-zinc-200 dark:hover:file:bg-zinc-600
                                           cursor-pointer" />
                                <flux:error name="newSheetFile" />
                            </flux:field>
                        </div>

                        <div class="flex justify-end">
                            <flux:button type="button" wire:click="addSheet" variant="primary" icon="plus"
                                wire:loading.attr="disabled">
                                Add Sheet
                            </flux:button>
                        </div>
                    </div>
                </div>
            </flux:card>
        </div>
    @endif
</div>
