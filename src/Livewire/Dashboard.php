<?php

namespace Platform\FoodService\Livewire;

use Livewire\Component;
use Platform\FoodService\Models\FsAllergen;
use Platform\FoodService\Models\FsAdditive;
use Platform\FoodService\Models\FsAttribute;

class Dashboard extends Component
{
    public function render()
    {
        $counts = [
            'allergens' => [
                'total' => FsAllergen::count(),
                'active' => FsAllergen::where('is_active', true)->count(),
                'inactive' => FsAllergen::where('is_active', false)->count(),
            ],
            'additives' => [
                'total' => FsAdditive::count(),
                'active' => FsAdditive::where('is_active', true)->count(),
                'inactive' => FsAdditive::where('is_active', false)->count(),
            ],
            'attributes' => [
                'total' => FsAttribute::count(),
                'active' => FsAttribute::where('is_active', true)->count(),
                'inactive' => FsAttribute::where('is_active', false)->count(),
            ],
        ];

        $recent = [
            'allergens' => FsAllergen::orderBy('created_at', 'desc')->take(5)->get(),
            'additives' => FsAdditive::orderBy('created_at', 'desc')->take(5)->get(),
            'attributes' => FsAttribute::orderBy('created_at', 'desc')->take(5)->get(),
        ];

        return view('foodservice::livewire.dashboard', [
            'counts' => $counts,
            'recent' => $recent,
        ])
            ->layout('platform::layouts.app');
    }
}