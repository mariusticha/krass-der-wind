<?php

namespace App\Livewire\Pages\Sheets;

use App\Models\Part;
use App\Models\Sheet;
use App\Models\Song;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Factory;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public ?int $sheetId = null;

    public ?int $songId = null;

    public ?int $partId = null;

    #[Validate('file|mimes:pdf|max:1024')] // 1MB Max
    public $sheet;

    public Collection $songs;

    public Collection $parts;

    protected function rules(): array
    {
        return [
            'songId' => [
                'required',
                'int',
            ],
            'partId' => [
                'required',
                'int',
            ],
        ];
    }

    public function mount(?Sheet $sheet = null): void
    {
        if ($sheet && $sheet->exists) {
            $this->sheetId = $sheet->id;
            $this->songId = $sheet->songId;
            $this->partId = $sheet->partId;
            $this->sheet = $sheet->file_path;
        }

        $this->songs = Song::pluck('name', 'id');

        $this->parts = Part::pluck('name', 'id');
    }

    public function save(): void
    {
        $validated = $this->validate();

        if ($this->sheetId) {
            $sheet = Sheet::findOrFail($this->sheetId);

            $sheet->update($validated);

            $message = 'Sheet updated successfully!';
        } else {
            $filePath = $this->sheet->storeAs(
                path: 'sheets',
                name: now()->toIso8601String() . '_' . $this->sheet->getClientOriginalName(),
            );

            Sheet::create([
                'song_id' => $validated['songId'],
                'part_id' => $validated['partId'],
                'file_path' => $filePath
            ]);

            $message = 'Sheet created successfully!';
        }

        session()->flash('message', $message);

        $this->redirect(route('sheets.index'), navigate: true);
    }

    public function cancel(): void
    {
        $this->redirect(route('sheets.index'), navigate: true);
    }

    public function render(): Factory | View
    {
        return view('livewire.pages.sheets.edit');
    }
}
