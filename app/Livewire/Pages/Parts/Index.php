<?php

namespace App\Livewire\Pages\Parts;

use Illuminate\Database\Eloquent\Builder;
use App\Concerns\Searchable;
use App\Models\Part;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use Searchable;
    use WithPagination;

    #[Url(as: 'view')]
    public ?int $viewingId = null;

    public bool $showViewModal = false;

    public function mount(): void
    {
        if ($this->viewingId !== null) {
            $this->showViewModal = true;
        }
    }

    public function viewRecord(int $id): void
    {
        $this->viewingId = $id;
        $this->showViewModal = true;
    }

    public function updatedShowViewModal(bool $value): void
    {
        if (! $value) {
            $this->viewingId = null;
        }
    }

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
        $viewingRecord = $this->viewingId !== null
            ? Part::with(['songs'])->find($this->viewingId)
            : null;

        $parts = Part::query()
            ->withCount('songs')
            ->tap(fn(Builder $q): Builder => $this->applySearchFilter($q, ['name']))
            ->paginate(10);

        return view('livewire.pages.parts.index', [
            'parts' => $parts,
            'viewingRecord' => $viewingRecord,
        ])->layoutData([
            'titleAddition' => __('Parts'),
        ]);
    }
}
