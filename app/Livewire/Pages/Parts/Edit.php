<?php

namespace App\Livewire\Pages\Parts;

use App\Models\Part;
use Illuminate\Contracts\View\View;
use Illuminate\View\Factory;
use Livewire\Component;

class Edit extends Component
{
    public ?int $partId = null;

    public string $name = '';

    protected function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                sprintf('unique:parts,name,%s', $this->partId ?? 'NULL'),
            ],
        ];
    }

    public function mount(?Part $part = null): void
    {
        if ($part && $part->exists) {
            $this->partId = $part->id;
            $this->name = $part->name;
        }
    }

    public function save(): void
    {
        $validated = $this->validate();

        if ($this->partId) {
            $part = Part::findOrFail($this->partId);

            $part->update($validated);

            $message = 'Part updated successfully!';
        } else {
            Part::create($validated);

            $message = 'Part created successfully!';
        }

        session()->flash('message', $message);

        $this->redirect(route('parts.index'), navigate: true);
    }

    public function cancel(): void
    {
        $this->redirect(route('parts.index'), navigate: true);
    }

    public function render(): Factory | View
    {
        return view('livewire.pages.parts.edit');
    }
}
