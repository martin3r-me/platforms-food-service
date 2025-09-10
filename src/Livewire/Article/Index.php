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

class Index extends Component
{
    // Modal State
    public $modalShow = false;
    
    // Sorting
    public $sortField = 'name';
    public $sortDirection = 'asc';
    
    // Form Data
    public $name = '';
    public $description = '';
    public $article_number = '';
    public $ean = '';
    public $brand_id = '';
    public $manufacturer_id = '';
    public $article_category_id = '';
    public $storage_type_id = '';
    public $base_unit_id = '';
    public $vat_category_id = '';
    public $nutritional_info = [];
    public $is_active = true;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'article_number' => 'nullable|string|max:255|unique:fs_articles,article_number',
        'ean' => 'nullable|string|max:255',
        'brand_id' => 'nullable|exists:fs_brands,id',
        'manufacturer_id' => 'nullable|exists:fs_manufacturers,id',
        'article_category_id' => 'nullable|exists:fs_article_categories,id',
        'storage_type_id' => 'nullable|exists:fs_storage_types,id',
        'base_unit_id' => 'nullable|exists:fs_base_units,id',
        'vat_category_id' => 'nullable|exists:fs_vat_categories,id',
        'nutritional_info' => 'nullable|array',
        'is_active' => 'boolean',
    ];

    #[Computed]
    public function articles()
    {
        $query = FsArticle::with([
            'brand', 
            'manufacturer', 
            'articleCategory', 
            'storageType', 
            'baseUnit', 
            'vatCategory'
        ])->forTeam(auth()->user()->currentTeam->id);

        if ($this->sortField === 'name') {
            $query->orderBy('name', $this->sortDirection);
        } else {
            $query->orderBy($this->sortField, $this->sortDirection);
        }

        return $query->get();
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


    public function createArticle()
    {
        $this->validate();
        
        $article = FsArticle::create([
            'name' => $this->name,
            'description' => $this->description,
            'article_number' => $this->article_number,
            'ean' => $this->ean,
            'brand_id' => $this->brand_id ?: null,
            'manufacturer_id' => $this->manufacturer_id ?: null,
            'article_category_id' => $this->article_category_id ?: null,
            'storage_type_id' => $this->storage_type_id ?: null,
            'base_unit_id' => $this->base_unit_id ?: null,
            'vat_category_id' => $this->vat_category_id ?: null,
            'nutritional_info' => $this->nutritional_info,
            'is_active' => $this->is_active,
            'team_id' => auth()->user()->currentTeam->id,
            'created_by_user_id' => auth()->id(),
            'owned_by_user_id' => auth()->id(),
        ]);

        $this->resetForm();
        $this->modalShow = false;
        
        session()->flash('message', 'Artikel erfolgreich erstellt!');
    }

    public function resetForm()
    {
        $this->reset([
            'name', 
            'description', 
            'article_number',
            'ean',
            'brand_id',
            'manufacturer_id',
            'article_category_id',
            'storage_type_id',
            'base_unit_id',
            'vat_category_id',
            'nutritional_info',
            'is_active'
        ]);
        $this->is_active = true;
    }

    public function openCreateModal()
    {
        $this->modalShow = true;
    }

    public function closeCreateModal()
    {
        $this->modalShow = false;
        $this->resetForm();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        return view('foodservice::livewire.article.index')
            ->layout('platform::layouts.app');
    }
}
