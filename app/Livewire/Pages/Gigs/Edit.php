<?php

namespace App\Livewire\Pages\Gigs;

use App\Models\Gig;
use App\Models\Song;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Edit extends Component
{
    public ?int $gigId = null;

    public string $name = '';

    public string $date = '';

    public string $time = '';

    public string $location = '';

    public string $city = '';

    public string $description = '';

    public bool $isPublic = true;

    // Song management
    public array $selectedSongs = [];

    public string $songSearch = '';

    public bool $showAddSongModal = false;

    public string $newSongName = '';

    public string $newSongArtist = '';

    public ?int $newSongYear = null;

    public string $newSongDescription = '';

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'time' => ['nullable', 'date_format:H:i'],
            'location' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'isPublic' => ['boolean'],
            'selectedSongs' => ['array'],
            'selectedSongs.*.id' => ['required', 'exists:songs,id'],
            'selectedSongs.*.notes' => ['nullable', 'string'],
        ];
    }

    public function mount(?Gig $gig = null): void
    {
        if ($gig && $gig->exists) {
            $this->gigId = $gig->id;
            $this->name = $gig->name;
            $this->date = $gig->date->format('Y-m-d');
            $this->time = $gig->time?->format('H:i') ?? '';
            $this->location = $gig->location;
            $this->city = $gig->city;
            $this->description = $gig->description ?? '';
            $this->isPublic = $gig->is_public;

            // Load songs
            $this->selectedSongs = $gig->songs->map(fn($song): array => [
                'id' => $song->id,
                'name' => $song->name,
                'artist' => $song->artist,
                'year' => $song->year,
                'notes' => $song->pivot->notes,
            ])->toArray();
        }
    }

    public function getAvailableSongsProperty()
    {
        $query = Song::query()->orderBy('artist')->orderBy('name');

        if ($this->songSearch !== '' && $this->songSearch !== '0') {
            $query->where(function ($q): void {
                $q->where('name', 'like', '%' . $this->songSearch . '%')
                    ->orWhere('artist', 'like', '%' . $this->songSearch . '%');
            });
        }

        return $query->limit(50)->get();
    }

    public function addSelectedSong(int $songId): void
    {
        $song = Song::findOrFail($songId);

        // Check if already added
        if (collect($this->selectedSongs)->contains('id', $songId)) {
            return;
        }

        $this->selectedSongs[] = [
            'id' => $song->id,
            'name' => $song->name,
            'artist' => $song->artist,
            'year' => $song->year,
            'notes' => '',
        ];

        $this->songSearch = '';
    }

    public function removeSelectedSong(int $index): void
    {
        unset($this->selectedSongs[$index]);
        $this->selectedSongs = array_values($this->selectedSongs);
    }

    public function openAddSongModal(): void
    {
        $this->showAddSongModal = true;
        $this->newSongName = '';
        $this->newSongArtist = '';
        $this->newSongYear = null;
        $this->newSongDescription = '';
    }

    public function closeAddSongModal(): void
    {
        $this->showAddSongModal = false;
    }

    public function createAndAddSong(): void
    {
        $this->validate([
            'newSongName' => ['required', 'string', 'max:255'],
            'newSongArtist' => ['required', 'string', 'max:255'],
            'newSongYear' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'newSongDescription' => ['nullable', 'string'],
        ]);

        $song = Song::create([
            'name' => $this->newSongName,
            'artist' => $this->newSongArtist,
            'year' => $this->newSongYear,
            'description' => $this->newSongDescription,
        ]);

        $this->addSelectedSong($song->id);
        $this->closeAddSongModal();
    }

    public function save(): void
    {
        $validated = $this->validate();

        // Map to correct database column name
        $validated['is_public'] = $validated['isPublic'];
        unset($validated['isPublic']);
        unset($validated['selectedSongs']);

        if ($this->gigId) {
            $gig = Gig::findOrFail($this->gigId);
            $gig->update($validated);
            $message = 'Gig updated successfully!';
        } else {
            $gig = Gig::create($validated);
            $message = 'Gig created successfully!';
        }

        // Sync songs
        $syncData = [];
        foreach ($this->selectedSongs as $song) {
            $syncData[$song['id']] = [
                'notes' => $song['notes'] ?: null,
            ];
        }

        $gig->songs()->sync($syncData);

        session()->flash('message', $message);
        $this->redirect(route('gigs.index'), navigate: true);
    }

    public function cancel(): void
    {
        $this->redirect(route('gigs.index'), navigate: true);
    }

    public function render(): Factory | View
    {
        return view('livewire.pages.gigs.edit');
    }
}
