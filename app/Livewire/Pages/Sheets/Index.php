<?php

namespace App\Livewire\Pages\Sheets;

use App\Models\Part;
use App\Models\Sheet;
use Livewire\Attributes\On;
use Livewire\Component;

class Index extends Component
{
    public function deleteSheet(Sheet $sheet): void
    {
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
