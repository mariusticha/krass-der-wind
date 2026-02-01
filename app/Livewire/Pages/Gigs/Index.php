<?php

namespace App\Livewire\Pages\Gigs;

use App\Models\Gig;
use Livewire\Component;

class Index extends Component
{
    public $upcomingGigs;
    public $pastGigs;

    public function mount()
    {
        $this->loadGigs();
    }

    public function loadGigs()
    {
        $upcomingQuery = Gig::query();
        $pastQuery = Gig::query();

        // Show only public gigs when not authenticated
        if (!auth()->check()) {
            $upcomingQuery->public();
            $pastQuery->public();
        }

        // Load user's RSVP/attendance data if authenticated
        if (auth()->check()) {
            $upcomingQuery->with(['users' => function ($q) {
                $q->where('user_id', auth()->id());
            }]);
            $pastQuery->with(['users' => function ($q) {
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

    public function render()
    {
        return view('livewire.pages.gigs.index');
    }
}
