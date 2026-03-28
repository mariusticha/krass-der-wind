<?php

namespace App\Livewire\Pages\Parts;

use App\Concerns\Searchable;
use App\Models\Part;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use Searchable;
    use WithPagination;
    public function deletePart(Part $part): void
    {
        if (! Auth::check() || ! Auth::user()->hasVerifiedEmail()) {
            return;
        }

        $part->delete();

        $this->dispatch('part-deleted');
    }

    #[On('part-saved')]
    #[On('part-deleted')]
    public function render(): Factory | View
    {
        $parts = Part::query()
            ->tap(fn($q) => $this->applySearchFilter($q, ['name']))
            ->paginate(10);

        return view('livewire.pages.parts.index', [
            'parts' => $parts,
        ])->layoutData([
            'titleAddition' => __('Parts'),
        ]);
    }
}
