<?php

namespace App\Livewire\Pages\Gigs;

use App\Models\Gig;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class Index extends Component
{
    public $upcomingGigs;

    public $pastGigs;

    public $showAttendeesModal = false;

    public $selectedGig;

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

        // Load all users with pivot data for counts and current user status
        $upcomingQuery->with('users');
        $pastQuery->with('users');

        $this->upcomingGigs = $upcomingQuery->upcoming()->get();
        $this->pastGigs = $pastQuery->past()->get();
    }

    public function deleteGig(Gig $gig): void
    {
        $gig->delete();

        $this->dispatch('gig-deleted');
        $this->loadGigs();
    }

    public function showAttendees(Gig $gig): void
    {
        $this->selectedGig = $gig->load('users');
        $this->showAttendeesModal = true;
    }

    public function closeAttendeesModal(): void
    {
        $this->showAttendeesModal = false;
        $this->selectedGig = null;
    }

    public function toggleRsvp(Gig $gig): void
    {
        $user = auth()->user();
        $pivot = $user->gigs()->where('gig_id', $gig->id)->first();

        if ($pivot && $pivot->pivot->rsvp_status === 'yes') {
            // Currently attending -> remove RSVP
            $user->gigs()->detach($gig->id);
        } else {
            // Not attending or no response -> set to attending
            $user->gigs()->syncWithoutDetaching([
                $gig->id => ['rsvp_status' => 'yes', 'updated_at' => now()],
            ]);
        }

        $this->loadGigs();
    }

    public function toggleAttendance(Gig $gig): void
    {
        $user = auth()->user();
        $pivot = $user->gigs()->where('gig_id', $gig->id)->first();

        if ($pivot && $pivot->pivot->attended) {
            // Currently marked as attended -> mark as not attended
            $user->gigs()->syncWithoutDetaching([
                $gig->id => ['attended' => false, 'attended_at' => null],
            ]);
        } else {
            // Not attended -> mark as attended
            $user->gigs()->syncWithoutDetaching([
                $gig->id => ['attended' => true, 'attended_at' => now()],
            ]);
        }

        $this->loadGigs();
    }

    public function togglePublic(Gig $gig): void
    {
        $gig->update(['is_public' => ! $gig->is_public]);
        $this->loadGigs();
    }

    public function render(): Factory | View
    {
        return view('livewire.pages.gigs.index')
            ->layout('layouts::app');
    }
}
