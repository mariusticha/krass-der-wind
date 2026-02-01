<?php

use App\Models\Gig;
use Livewire\Attributes\Reactive;
use Livewire\Component;

new class extends Component
{
    #[Reactive]
    public Gig $gig;

    public ?string $rsvpStatus = null;
    public int $rsvpCount = 0;

    public function mount(): void
    {
        $this->loadRsvpStatus();
    }

    public function loadRsvpStatus(): void
    {
        $userRsvp = $this->gig->users()
            ->where('user_id', auth()->id())
            ->first();

        $this->rsvpStatus = $userRsvp?->pivot?->rsvp_status;

        $this->rsvpCount = $this->gig->users()
            ->whereNotNull('gig_user.rsvp_status')
            ->count();
    }

    public function rsvp(string $status): void
    {
        $this->gig->users()->syncWithoutDetaching([
            auth()->id() => [
                'rsvp_status' => $status,
                'rsvp_at' => now(),
            ],
        ]);

        $this->rsvpStatus = $status;
        $this->loadRsvpStatus();

        $this->dispatch('rsvp-updated');
    }
};
?>

<div>
    <x-livewire:gigs.rsvp-button />
</div>
