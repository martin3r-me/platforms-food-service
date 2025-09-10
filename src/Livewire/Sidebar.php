<?php

namespace Platform\FoodService\Livewire;

use Livewire\Component;

class Sidebar extends Component
{
    public function render()
    {
        return view('foodservice::livewire.sidebar')
            ->layout('platform::layouts.app');
    }
}