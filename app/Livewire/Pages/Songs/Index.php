<?php

namespace App\Livewire\Pages\Songs;

use Illuminate\Database\Eloquent\Builder;
use App\Concerns\Searchable;
use App\Models\Song;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use Searchable;
    use WithPagination;

    #[Url(as: 'view')]
    public ?int $viewingId = null;

    public bool $showViewModal = false;

    public function mount(): void
    {
        if ($this->viewingId !== null && Auth::check()) {
            $this->showViewModal = true;
        }
    }

    public function viewRecord(int $id): void
    {
        if (! Auth::check()) {
            return;
        }

        $this->viewingId = $id;
        $this->showViewModal = true;
    }

    public function updatedShowViewModal(bool $value): void
    {
        if (! $value) {
            $this->viewingId = null;
        }
    }

    public function deleteSong(Song $song): void
    {
        if (! Auth::check() || ! Auth::user()->hasVerifiedEmail()) {
            return;
        }

        $song->delete();

        $this->dispatch('song-deleted');
    }

    #[On('song-saved')]
    #[On('song-deleted')]
    public function render(): Factory | View
    {
        $viewingRecord = $this->viewingId !== null
            ? Song::with(['sheets.part', 'gigs'])->find($this->viewingId)
            : null;

        $songs = Song::query()
            ->withCount('gigs')
            ->tap(fn(Builder $q): Builder => $this->applySearchFilter($q, ['name', 'artist']))
            ->orderBy('artist')
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.pages.songs.index', [
            'songs' => $songs,
            'viewingRecord' => $viewingRecord,
        ])->layoutData([
            'titleAddition' => __('Songs'),
        ]);
    }
}
