<?php

namespace Platform\FoodService\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Page extends Component
{
    public function __construct(
        public string $title,
        public ?string $icon = null,
        public ?string $description = null,
        public array $breadcrumbs = [],
        public string $sidebarTitle = 'Modul-Navigation',
        public string $activityTitle = 'Aktivitäten'
    ) {
    }

    public function render(): View
    {
        return view('foodservice::components.page');
    }
}

