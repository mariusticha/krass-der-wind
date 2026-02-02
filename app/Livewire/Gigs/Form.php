<?php

namespace App\Livewire\Gigs;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use App\Models\Gig;
use Livewire\Attributes\On;
use Livewire\Component;

class Form extends Component
{
    public $gigId;

    public $name = '';

    public $date = '';

    public $time = '';

    public $location = '';

    public $city = '';

    public $description = '';

    public $playlist = [''];

    public $isPublic = true;

    public $showModal = false;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'time' => ['nullable', 'date_format:H:i'],
            'location' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'playlist' => ['nullable', 'array'],
            'playlist.*' => ['nullable', 'string'],
            'isPublic' => ['boolean'],
        ];
    }

    #[On('open-gig-form')]
    public function openForm(): void
    {
        $this->reset();
        $this->playlist = [''];
        $this->showModal = true;
    }

    #[On('edit-gig')]
    public function editGig($gigId): void
    {
        $gig = Gig::findOrFail($gigId);

        $this->gigId = $gig->id;
        $this->name = $gig->name;
        $this->date = $gig->date->format('Y-m-d');
        $this->time = $gig->time?->format('H:i');
        $this->location = $gig->location;
        $this->city = $gig->city;
        $this->description = $gig->description ?? '';
        $this->playlist = empty($gig->playlist) ? [''] : $gig->playlist;
        $this->isPublic = $gig->is_public;
        $this->showModal = true;
    }

    public function addSong(): void
    {
        $this->playlist[] = '';
    }

    public function removeSong($index): void
    {
        unset($this->playlist[$index]);
        $this->playlist = array_values($this->playlist);

        if ($this->playlist === []) {
            $this->playlist = [''];
        }
    }

    public function save(): void
    {
        $validated = $this->validate();

        // Filter out empty songs
        $validated['playlist'] = array_filter(
            array_map(trim(...), $validated['playlist'] ?? []),
        );

        if (empty($validated['playlist'])) {
            $validated['playlist'] = null;
        }

        // Map to correct database column name
        $validated['is_public'] = $validated['isPublic'];
        unset($validated['isPublic']);

        if ($this->gigId) {
            $gig = Gig::findOrFail($this->gigId);
            $gig->update($validated);
            $message = 'Gig updated successfully!';
        } else {
            Gig::create($validated);
            $message = 'Gig created successfully!';
        }

        $this->dispatch('gig-saved');
        $this->closeModal();

        session()->flash('message', $message);
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->reset();
    }

    public function render(): Factory|View
    {
        return view('livewire.gigs.form');
    }
}
