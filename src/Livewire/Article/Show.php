<?php

namespace Platform\FoodService\Livewire\Article;

use Livewire\Component;
use Livewire\Attributes\Computed;
use Platform\FoodService\Models\FsArticle;
use Platform\FoodService\Models\FsBrand;
use Platform\FoodService\Models\FsManufacturer;
use Platform\FoodService\Models\FsArticleCategory;
use Platform\FoodService\Models\FsStorageType;
use Platform\FoodService\Models\FsBaseUnit;
use Platform\FoodService\Models\FsVatCategory;
use Platform\FoodService\Models\FsAllergen;
use Platform\FoodService\Models\FsAdditive;
use Platform\FoodService\Models\FsAttribute;

class Show extends Component
{
    public FsArticle $article;

    // Settings Modal
    public $settingsModalShow = false;
    
    // Settings Form
    public $settingsForm = [
        'description' => '',
        'is_active' => true,
    ];

    public function mount(FsArticle $article)
    {
        $this->article = $article->load([
            'brand', 
            'manufacturer', 
            'articleCategory', 
            'storageType', 
            'baseUnit', 
            'vatCategory',
            'allergens',
            'additives',
            'attributes'
        ]);
        
        // Settings Form initialisieren
        $this->settingsForm = [
            'description' => $this->article->description ?? '',
            'is_active' => $this->article->is_active,
        ];
    }

    public function rules(): array
    {
        return [
            'article.name' => 'required|string|max:255',
            'article.article_number' => 'nullable|string|max:255|unique:fs_articles,article_number,' . $this->article->id,
            'article.ean' => 'nullable|string|max:255',
            'settingsForm.description' => 'nullable|string',
            'settingsForm.is_active' => 'boolean',
        ];
    }

    public function save(): void
    {
        $this->validate();
        $this->article->save();

        session()->flash('message', 'Artikel erfolgreich aktualisiert.');
    }

    public function deleteItem(): void
    {
        $this->article->delete();
        
        session()->flash('message', 'Artikel erfolgreich gelöscht.');
        
        return redirect()->route('foodservice.articles.index');
    }

    public function saveSettings(): void
    {
        $this->validate([
            'settingsForm.description' => 'nullable|string',
            'settingsForm.is_active' => 'boolean',
        ]);

        $this->article->update([
            'description' => $this->settingsForm['description'],
            'is_active' => $this->settingsForm['is_active'],
        ]);

        $this->settingsModalShow = false;
        session()->flash('message', 'Einstellungen erfolgreich gespeichert.');
    }

    #[Computed]
    public function availableBrands()
    {
        return FsBrand::where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function availableManufacturers()
    {
        return FsManufacturer::where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function availableArticleCategories()
    {
        return FsArticleCategory::where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function availableStorageTypes()
    {
        return FsStorageType::where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function availableBaseUnits()
    {
        return FsBaseUnit::where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function availableVatCategories()
    {
        return FsVatCategory::where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function availableAllergens()
    {
        return FsAllergen::where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function availableAdditives()
    {
        return FsAdditive::where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function availableAttributes()
    {
        return FsAttribute::where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function stats()
    {
        return [
            'total_allergens' => $this->article->allergens->count(),
            'total_additives' => $this->article->additives->count(),
            'total_attributes' => $this->article->attributes->count(),
        ];
    }

    public function render()
    {
        return view('foodservice::livewire.article.show')
            ->layout('platform::layouts.app');
    }
}
