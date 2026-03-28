<?php

namespace App\Livewire\Pages\Songs;

use App\Models\Part;
use App\Models\Sheet;
use App\Models\Song;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public ?int $songId = null;

    public string $name = '';

    public string $artist = '';

    public ?int $year = null;

    public string $description = '';

    // Sheet management
    public ?int $newSheetPartId = null;

    #[Validate('nullable|file|mimes:pdf|max:10240')]
    public $newSheetFile;

    public Collection $parts;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'artist' => ['required', 'string', 'max:255'],
            'year' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'description' => ['nullable', 'string'],
        ];
    }

    public function mount(?Song $song = null): void
    {
        if ($song && $song->exists) {
            $this->songId = $song->id;
            $this->name = $song->name;
            $this->artist = $song->artist;
            $this->year = $song->year;
            $this->description = $song->description ?? '';
        }

        $this->parts = Part::orderBy('name')->pluck('name', 'id');
    }

    public function addSheet(): void
    {
        $this->validate([
            'newSheetPartId' => ['required', 'integer', 'exists:parts,id'],
            'newSheetFile' => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        $alreadyExists = Sheet::query()
            ->where('song_id', $this->songId)
            ->where('part_id', $this->newSheetPartId)
            ->exists();

        if ($alreadyExists) {
            $this->addError('newSheetPartId', __('A sheet for this part already exists. Remove it first to replace it.'));

            return;
        }

        $filePath = $this->newSheetFile->storeAs(
            path: 'sheets',
            name: now()->toIso8601String() . '_' . $this->newSheetFile->getClientOriginalName(),
        );

        Sheet::create([
            'song_id' => $this->songId,
            'part_id' => $this->newSheetPartId,
            'file_path' => $filePath,
        ]);

        $this->reset('newSheetPartId', 'newSheetFile');
    }

    public function removeSheet(Sheet $sheet): void
    {
        abort_unless($sheet->song_id === $this->songId, 403);

        Storage::delete($sheet->file_path);

        $sheet->delete();
    }

    public function save(): void
    {
        $validated = $this->validate($this->rules());

        if ($this->songId) {
            $song = Song::findOrFail($this->songId);
            $song->update($validated);
            $message = __('Song updated successfully!');
        } else {
            Song::create($validated);
            $message = __('Song created successfully!');
        }

        session()->flash('message', $message);
        $this->redirect(route('songs.index'), navigate: true);
    }

    public function cancel(): void
    {
        $this->redirect(route('songs.index'), navigate: true);
    }

    public function render(): Factory | View
    {
        $sheets = $this->songId
            ? Sheet::query()
            ->where('song_id', $this->songId)
            ->with('part')
            ->orderBy('created_at')
            ->get()
            : collect();

        return view('livewire.pages.songs.edit', [
            'sheets' => $sheets,
        ]);
    }
}
