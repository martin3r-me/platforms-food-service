<?php

namespace Platform\FoodService\Livewire;

use Livewire\Component;
use Platform\FoodService\Models\FsAllergen;
use Platform\FoodService\Models\FsAdditive;
use Platform\FoodService\Models\FsAttribute;
use Platform\FoodService\Models\FsVatCategory;
use Platform\FoodService\Models\FsArticleCluster;
use Platform\FoodService\Models\FsArticleCategory;
use Platform\FoodService\Models\FsStorageType;
use Platform\FoodService\Models\FsBaseUnit;

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
            'vat_categories' => [
                'total' => FsVatCategory::count(),
                'active' => FsVatCategory::where('is_active', true)->count(),
                'inactive' => FsVatCategory::where('is_active', false)->count(),
            ],
            'article_clusters' => [
                'total' => FsArticleCluster::count(),
                'active' => FsArticleCluster::where('is_active', true)->count(),
                'inactive' => FsArticleCluster::where('is_active', false)->count(),
            ],
            'article_categories' => [
                'total' => FsArticleCategory::count(),
                'active' => FsArticleCategory::where('is_active', true)->count(),
                'inactive' => FsArticleCategory::where('is_active', false)->count(),
            ],
            'storage_types' => [
                'total' => FsStorageType::count(),
                'active' => FsStorageType::where('is_active', true)->count(),
                'inactive' => FsStorageType::where('is_active', false)->count(),
            ],
            'base_units' => [
                'total' => FsBaseUnit::count(),
                'active' => FsBaseUnit::where('is_active', true)->count(),
                'inactive' => FsBaseUnit::where('is_active', false)->count(),
            ],
        ];

        $recent = [
            'allergens' => FsAllergen::orderBy('created_at', 'desc')->take(5)->get(),
            'additives' => FsAdditive::orderBy('created_at', 'desc')->take(5)->get(),
            'attributes' => FsAttribute::orderBy('created_at', 'desc')->take(5)->get(),
            'vat_categories' => FsVatCategory::orderBy('created_at', 'desc')->take(5)->get(),
            'article_clusters' => FsArticleCluster::orderBy('created_at', 'desc')->take(5)->get(),
            'article_categories' => FsArticleCategory::orderBy('created_at', 'desc')->take(5)->get(),
            'storage_types' => FsStorageType::orderBy('created_at', 'desc')->take(5)->get(),
            'base_units' => FsBaseUnit::orderBy('created_at', 'desc')->take(5)->get(),
        ];

        return view('foodservice::livewire.dashboard', [
            'counts' => $counts,
            'recent' => $recent,
        ])
            ->layout('platform::layouts.app');
    }
}