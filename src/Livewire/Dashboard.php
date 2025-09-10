<?php

namespace Platform\FoodService\Livewire;

use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        return view('food-service::livewire.dashboard')
            ->layout('platform::layouts.app');
    }
}