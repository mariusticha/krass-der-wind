<?php

namespace App\Livewire\Pages\Gigs;

use App\Models\Gig;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;

class Index extends Component
{
    public $upcomingGigs;

    public $pastGigs;

    #[Url(as: 'view')]
    public ?int $viewingId = null;

    public bool $showViewModal = false;

    public ?Gig $viewingGig = null;

    public function mount(): void
    {
        $this->loadGigs();

        if ($this->viewingId !== null) {
            $this->loadViewingGig();
            $this->showViewModal = true;
        }
    }

    #[On('gig-saved')]
    #[On('gig-deleted')]
    public function loadGigs(): void
    {
        $upcomingQuery = Gig::query();
        $pastQuery = Gig::query();

        // Show only public gigs when not authenticated or email not verified
        if (! Auth::check() || ! Auth::user()->hasVerifiedEmail()) {
            $upcomingQuery->public();
            $pastQuery->public();
        }

        // Load all users with pivot data for counts and current user status
        $upcomingQuery->with(['users', 'songs']);
        $pastQuery->with(['users', 'songs']);

        $this->upcomingGigs = $upcomingQuery->upcoming()->get();
        $this->pastGigs = $pastQuery->past()->get();
    }

    public function viewRecord(int $id): void
    {
        $this->viewingId = $id;
        $this->loadViewingGig();
        $this->showViewModal = true;
    }

    public function updatedShowViewModal(bool $value): void
    {
        if (! $value) {
            $this->viewingId = null;
            $this->viewingGig = null;
        }
    }

    private function loadViewingGig(): void
    {
        $this->viewingGig = Gig::with(['users', 'songs'])->find($this->viewingId);
    }

    public function deleteGig(Gig $gig): void
    {
        if (! Auth::check() || ! Auth::user()->hasVerifiedEmail()) {
            return;
        }

        $gig->delete();

        $this->dispatch('gig-deleted');
        $this->loadGigs();
    }

    public function toggleRsvp(Gig $gig): void
    {
        if (! Auth::check() || ! Auth::user()->hasVerifiedEmail()) {
            return;
        }

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
        if (! Auth::check() || ! Auth::user()->hasVerifiedEmail()) {
            return;
        }

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
        if (! Auth::check() || ! Auth::user()->hasVerifiedEmail()) {
            return;
        }

        $gig->update(['is_public' => ! $gig->is_public]);
        $this->loadGigs();
    }

    public function render(): Factory | View
    {
        return view('livewire.pages.gigs.index')
            ->layoutData([
                'titleAddition' => __('Gigs'),
            ]);
    }
}
