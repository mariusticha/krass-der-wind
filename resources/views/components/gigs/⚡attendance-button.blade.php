<?php

use App\Models\Gig;
use Livewire\Attributes\Reactive;
use Livewire\Component;

new class extends Component
{
    #[Reactive]
    public Gig $gig;

    public bool $attended = false;
    public int $attendanceCount = 0;

    public function mount(): void
    {
        $this->loadAttendance();
    }

    public function loadAttendance(): void
    {
        $userAttendance = $this->gig->users()
            ->where('user_id', auth()->id())
            ->first();

        $this->attended = (bool) $userAttendance?->pivot?->attended;

        $this->attendanceCount = $this->gig->users()
            ->wherePivot('attended', true)
            ->count();
    }

    public function toggleAttendance(): void
    {
        $this->gig->users()->syncWithoutDetaching([
            auth()->id() => [
                'attended' => $this->attended,
                'attended_at' => $this->attended ? now() : null,
            ],
        ]);

        $this->loadAttendance();

        $this->dispatch('attendance-updated');
    }
};
?>

<div>
    <x-livewire:gigs.attendance-button />
</div>
