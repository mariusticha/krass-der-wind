<?php

namespace App\Livewire\Gigs;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use App\Models\Gig;
use Livewire\Attributes\Locked;
use Livewire\Component;

class RsvpButton extends Component
{
    #[Locked]
    public Gig $gig;

    public $rsvpStatus;

    public $rsvpCount = 0;

    public function mount(): void
    {
        $this->loadRsvpStatus();
    }

    public function loadRsvpStatus(): void
    {
        $pivot = $this->gig->users()->where('user_id', auth()->id())->first()?->pivot;
        $this->rsvpStatus = $pivot?->rsvp_status;
        $this->rsvpCount = $this->gig->users()->whereNotNull('rsvp_status')->count();
    }

    public function rsvp($status): void
    {
        if ($status === $this->rsvpStatus) {
            // Remove RSVP if clicking the same status
            $this->gig->users()->detach(auth()->id());
            $this->rsvpStatus = null;
        } else {
            // Update or create RSVP
            $this->gig->users()->syncWithoutDetaching([
                auth()->id() => [
                    'rsvp_status' => $status,
                    'rsvp_at' => now(),
                    'attended' => null,
                    'attended_at' => null,
                ],
            ]);
            $this->rsvpStatus = $status;
        }

        $this->dispatch('rsvp-updated');
        $this->loadRsvpStatus();
    }

    public function render(): Factory|View
    {
        return view('livewire.gigs.rsvp-button');
    }
}
