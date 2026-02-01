<?php

namespace App\Livewire\Pages\Gigs;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use App\Models\Gig;
use Livewire\Attributes\On;
use Livewire\Component;

class Index extends Component
{
    public $upcomingGigs;

    public $pastGigs;

    public function mount(): void
    {
        $this->loadGigs();
    }

    #[On('gig-saved')]
    #[On('gig-deleted')]
    public function loadGigs(): void
    {
        $upcomingQuery = Gig::query();
        $pastQuery = Gig::query();

        // Show only public gigs when not authenticated
        if (! auth()->check()) {
            $upcomingQuery->public();
            $pastQuery->public();
        }

        // Load user's RSVP/attendance data if authenticated
        if (auth()->check()) {
            $upcomingQuery->with(['users' => function ($q): void {
                $q->where('user_id', auth()->id());
            }]);
            $pastQuery->with(['users' => function ($q): void {
                $q->where('user_id', auth()->id());
            }]);
        }

        $this->upcomingGigs = $upcomingQuery->upcoming()->get();
        $this->pastGigs = $pastQuery->past()->get();
    }

    public function deleteGig(Gig $gig): void
    {
        $gig->delete();

        $this->dispatch('gig-deleted');
        $this->loadGigs();
    }

    public function openForm(): void
    {
        $this->dispatch('open-gig-form');
    }

    public function editGig($gigId): void
    {
        $this->dispatch('edit-gig', gigId: $gigId);
    }

    public function render(): Factory|View
    {
        return view('livewire.pages.gigs.index');
    }
}
