<?php

namespace App\Livewire\Pages\Sheets;

use App\Models\Sheet;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class Index extends Component
{
    public function deleteSheet(Sheet $sheet): void
    {
        if (! Auth::check() || ! Auth::user()->hasVerifiedEmail()) {
            return;
        }

        $sheet->delete();

        $this->dispatch('sheet-deleted');
    }

    #[On('sheet-saved')]
    #[On('sheet-deleted')]
    public function render()
    {
        $sheets = Sheet::query()
            ->paginate(10);

        return view('livewire.pages.sheets.index', [
            'sheets' => $sheets,
        ]);
    }
}
