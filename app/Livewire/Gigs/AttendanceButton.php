<?php

namespace App\Livewire\Gigs;

use App\Models\Gig;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class AttendanceButton extends Component
{
    #[Locked]
    public Gig $gig;

    public $attended = false;

    public $attendanceCount = 0;

    public function mount(): void
    {
        $this->loadAttendance();
    }

    public function loadAttendance(): void
    {
        $pivot = $this->gig->users()->where('user_id', auth()->id())->first()?->pivot;
        $this->attended = (bool) $pivot?->attended;
        $this->attendanceCount = $this->gig->users()->where('attended', true)->count();
    }

    public function toggleAttendance(): void
    {
        $newStatus = ! $this->attended;

        $this->gig->users()->syncWithoutDetaching([
            auth()->id() => [
                'attended' => $newStatus,
                'attended_at' => $newStatus ? now() : null,
            ],
        ]);

        $this->attended = $newStatus;
        $this->dispatch('attendance-updated');
        $this->loadAttendance();
    }

    public function render(): Factory | View
    {
        return view('livewire.gigs.attendance-button');
    }
}
