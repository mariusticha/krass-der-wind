<?php

namespace App\Livewire\Pages\Songs;

use App\Models\Song;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public function mount(): void
    {
        //
    }

    public function deleteSong(Song $song): void
    {
        $song->delete();

        $this->dispatch('song-deleted');
    }

    #[On('song-saved')]
    #[On('song-deleted')]
    public function render(): Factory | View
    {
        $songs = Song::query()
            ->withCount('gigs')
            ->orderBy('artist')
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.pages.songs.index', [
            'songs' => $songs,
        ]);
    }
}
