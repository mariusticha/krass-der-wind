<?php

namespace App\Livewire\Pages\Gigs;

use App\Models\Gig;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Edit extends Component
{
    public ?int $gigId = null;

    public string $name = '';

    public string $date = '';

    public string $time = '';

    public string $location = '';

    public string $city = '';

    public string $description = '';

    public array $playlist = [''];

    public bool $isPublic = true;

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

    public function mount(?Gig $gig = null): void
    {
        if ($gig && $gig->exists) {
            $this->gigId = $gig->id;
            $this->name = $gig->name;
            $this->date = $gig->date->format('Y-m-d');
            $this->time = $gig->time?->format('H:i') ?? '';
            $this->location = $gig->location;
            $this->city = $gig->city;
            $this->description = $gig->description ?? '';
            $this->playlist = empty($gig->playlist) ? [''] : $gig->playlist;
            $this->isPublic = $gig->is_public;
        }
    }

    public function addSong(): void
    {
        $this->playlist[] = '';
    }

    public function removeSong(int $index): void
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

        session()->flash('message', $message);
        $this->redirect(route('gigs.index'), navigate: true);
    }

    public function cancel(): void
    {
        $this->redirect(route('gigs.index'), navigate: true);
    }

    public function render(): Factory|View
    {
        return view('livewire.pages.gigs.edit');
    }
}
