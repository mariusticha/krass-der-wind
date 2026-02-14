<?php

namespace App\Livewire\Pages\Parts;

use App\Models\Part;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class Index extends Component
{
    public function deletePart(Part $part): void
    {
        $part->delete();

        $this->dispatch('part-deleted');
    }

    #[On('part-saved')]
    #[On('part-deleted')]
    public function render()
    {
        $parts = Part::query()
            ->paginate(10);

        return view('livewire.pages.parts.index', [
            'parts' => $parts,
        ]);
    }
}
