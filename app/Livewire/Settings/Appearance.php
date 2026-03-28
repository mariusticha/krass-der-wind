<?php

namespace App\Livewire\Settings;

use Illuminate\View\Factory;
use Illuminate\View\View;
use Livewire\Component;

class Appearance extends Component
{
    public function render(): Factory | View
    {
        return view('livewire.settings.appearance')
            ->layoutData([
                'titleAddition' => __('Appearance')
            ]);
    }
}
