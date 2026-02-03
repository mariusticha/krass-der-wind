<?php

namespace App\Livewire\Pages\Songs;

use App\Models\Song;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Edit extends Component
{
    public ?int $songId = null;

    public string $name = '';

    public string $artist = '';

    public ?int $year = null;

    public string $description = '';

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
    }

    public function save(): void
    {
        $validated = $this->validate();

        if ($this->songId) {
            $song = Song::findOrFail($this->songId);
            $song->update($validated);
            $message = 'Song updated successfully!';
        } else {
            Song::create($validated);
            $message = 'Song created successfully!';
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
        return view('livewire.pages.songs.edit');
    }
}
