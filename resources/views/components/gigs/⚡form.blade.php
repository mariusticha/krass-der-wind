<?php

use App\Models\Gig;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    public ?int $gigId = null;
    public string $name = '';
    public string $description = '';
    public string $date = '';
    public string $time = '';
    public string $location = '';
    public string $city = '';
    public array $playlist = [''];
    public bool $isPublic = true;

    public function mount(): void
    {
        $this->date = now()->addDays(7)->format('Y-m-d');
    }

    #[On('open-gig-form')]
    public function openModal(): void
    {
        $this->reset();
        $this->date = now()->addDays(7)->format('Y-m-d');
        $this->playlist = [''];
        $this->isPublic = true;

        $this->dispatch('modal-open', name: 'gig-form');
    }

    #[On('edit-gig')]
    public function editGig(int $gigId): void
    {
        $gig = Gig::findOrFail($gigId);

        $this->gigId = $gig->id;
        $this->name = $gig->name;
        $this->description = $gig->description ?? '';
        $this->date = $gig->date->format('Y-m-d');
        $this->time = $gig->time ? $gig->time->format('H:i') : '';
        $this->location = $gig->location;
        $this->city = $gig->city;
        $this->playlist = $gig->playlist ?? [''];
        $this->isPublic = $gig->is_public;

        $this->dispatch('modal-open', name: 'gig-form');
    }

    public function addSong(): void
    {
        $this->playlist[] = '';
    }

    public function removeSong(int $index): void
    {
        unset($this->playlist[$index]);
        $this->playlist = array_values($this->playlist);

        if (empty($this->playlist)) {
            $this->playlist = [''];
        }
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'time' => 'nullable|date_format:H:i',
            'location' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'playlist' => 'nullable|array',
            'playlist.*' => 'nullable|string|max:255',
            'isPublic' => 'boolean',
        ]);

        // Remove empty playlist entries
        $validated['playlist'] = array_values(array_filter($validated['playlist']));

        // Convert time if present
        if (!empty($validated['time'])) {
            $validated['time'] = $validated['date'].' '.$validated['time'];
        }

        $validated['is_public'] = $validated['isPublic'];
        unset($validated['isPublic']);

        if ($this->gigId) {
            $gig = Gig::findOrFail($this->gigId);
            $gig->update($validated);
        } else {
            Gig::create($validated);
        }

        $this->closeModal();
        $this->dispatch('gig-saved');
        $this->dispatch('$refresh');
    }

    public function closeModal(): void
    {
        $this->reset();
        $this->dispatch('modal-close', name: 'gig-form');
    }
};
?>

<div>
    <x-livewire:gigs.form />
</div>
