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
    
    // Modals
    public $modalShow = false;
    public $modalType = '';
    
    // Edit Modal
    public $editModalShow = false; // 'allergen', 'additive', 'attribute'
    
    // Track if form is dirty
    public $isDirty = false;
    
    // Relationship management
    public $selectedAllergens = [];
    public $selectedAdditives = [];
    public $selectedAttributes = [];
    
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
        
        // Relationship data initialisieren
        $this->selectedAllergens = $this->article->allergens->pluck('id')->toArray();
        $this->selectedAdditives = $this->article->additives->pluck('id')->toArray();
        $this->selectedAttributes = $this->article->attributes->pluck('id')->toArray();
    }

    public function updated($propertyName)
    {
        $this->isDirty = true;
    }

    public function rules(): array
    {
        return [
            'article.name' => 'required|string|max:255',
            'article.article_number' => 'nullable|string|max:255|unique:fs_articles,article_number,' . $this->article->id,
            'article.ean' => 'nullable|string|max:255',
            'article.brand_id' => 'nullable|exists:fs_brands,id',
            'article.manufacturer_id' => 'nullable|exists:fs_manufacturers,id',
            'article.article_category_id' => 'nullable|exists:fs_article_categories,id',
            'article.storage_type_id' => 'nullable|exists:fs_storage_types,id',
            'article.base_unit_id' => 'nullable|exists:fs_base_units,id',
            'article.vat_category_id' => 'nullable|exists:fs_vat_categories,id',
            'article.is_active' => 'boolean',
            'settingsForm.description' => 'nullable|string',
            'settingsForm.is_active' => 'boolean',
            // Supplier Article validation rules
            'newSupplierArticle.supplier_id' => 'required|exists:fs_suppliers,id',
            'newSupplierArticle.supplier_article_number' => 'nullable|string|max:255',
            'newSupplierArticle.supplier_ean' => 'nullable|string|max:255',
            'newSupplierArticle.purchase_price' => 'nullable|numeric|min:0',
            'newSupplierArticle.currency' => 'required|string|max:3',
            'newSupplierArticle.minimum_order_quantity' => 'nullable|integer|min:1',
            'newSupplierArticle.delivery_time_days' => 'nullable|integer|min:0',
            'newSupplierArticle.notes' => 'nullable|string',
            'newSupplierArticle.is_active' => 'boolean',
            // Edit Supplier Article validation rules
            'editSupplierArticle.supplier_id' => 'required|exists:fs_suppliers,id',
            'editSupplierArticle.supplier_article_number' => 'nullable|string|max:255',
            'editSupplierArticle.supplier_ean' => 'nullable|string|max:255',
            'editSupplierArticle.purchase_price' => 'nullable|numeric|min:0',
            'editSupplierArticle.currency' => 'required|string|max:3',
            'editSupplierArticle.minimum_order_quantity' => 'nullable|integer|min:1',
            'editSupplierArticle.delivery_time_days' => 'nullable|integer|min:0',
            'editSupplierArticle.notes' => 'nullable|string',
            'editSupplierArticle.is_active' => 'boolean',
        ];
    }

    public function save(): void
    {
        $this->validate();
        $this->article->save();
        $this->isDirty = false;

        session()->flash('message', 'Artikel erfolgreich aktualisiert.');
    }

    public function deleteItem()
    {
        $this->article->delete();
        
        session()->flash('message', 'Artikel erfolgreich gelöscht.');
        
        return redirect()->route('foodservice.articles.index');
    }

    public function openModal($type)
    {
        $this->modalType = $type;
        $this->modalShow = true;
        
        // Load available options based on type
        if ($type === 'allergen') {
            $this->selectedAllergens = $this->article->allergens->pluck('id')->toArray();
        } elseif ($type === 'additive') {
            $this->selectedAdditives = $this->article->additives->pluck('id')->toArray();
        } elseif ($type === 'attribute') {
            $this->selectedAttributes = $this->article->attributes->pluck('id')->toArray();
        }
    }

    public function openCreateModal()
    {
        $this->resetSupplierArticleForm();
        $this->modalType = 'supplier';
        $this->modalShow = true;
    }

    public function closeModal()
    {
        $this->modalShow = false;
        $this->modalType = '';
    }

    public function saveRelationships()
    {
        switch ($this->modalType) {
            case 'allergen':
                $this->article->allergens()->sync($this->selectedAllergens);
                $this->article->load(['allergens']);
                session()->flash('message', 'Allergene erfolgreich gespeichert.');
                break;
                
            case 'additive':
                $this->article->additives()->sync($this->selectedAdditives);
                $this->article->load(['additives']);
                session()->flash('message', 'Zusatzstoffe erfolgreich gespeichert.');
                break;
                
            case 'attribute':
                $this->article->attributes()->sync($this->selectedAttributes);
                $this->article->load(['attributes']);
                session()->flash('message', 'Attribute erfolgreich gespeichert.');
                break;
        }
        
        $this->closeModal();
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
            'total_suppliers' => $this->article->supplierArticles->count(),
        ];
    }

    protected $listeners = [
        'supplierArticleUpdated' => '$refresh',
    ];

    // Supplier Article Form
    public $newSupplierArticle = [
        'supplier_id' => '',
        'supplier_article_number' => '',
        'supplier_ean' => '',
        'purchase_price' => '',
        'currency' => 'EUR',
        'minimum_order_quantity' => '',
        'delivery_time_days' => '',
        'notes' => '',
        'is_active' => true,
    ];
    public $editingSupplierArticleId = null;
    
    // Edit Supplier Article Form
    public $editSupplierArticle = [
        'supplier_id' => '',
        'supplier_article_number' => '',
        'supplier_ean' => '',
        'purchase_price' => '',
        'currency' => 'EUR',
        'minimum_order_quantity' => '',
        'delivery_time_days' => '',
        'notes' => '',
        'is_active' => true,
    ];

    public function getAvailableSuppliersProperty()
    {
        return \Platform\FoodService\Models\FsSupplier::where('team_id', auth()->user()->currentTeam->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    public function getCurrenciesProperty()
    {
        return [
            'EUR' => 'Euro (EUR)',
            'USD' => 'US Dollar (USD)',
            'CHF' => 'Schweizer Franken (CHF)',
            'GBP' => 'Britisches Pfund (GBP)',
        ];
    }

    public function saveSupplierArticle()
    {
        $rules = [
            'newSupplierArticle.supplier_id' => 'required|exists:fs_suppliers,id',
            'newSupplierArticle.supplier_article_number' => 'nullable|string|max:255',
            'newSupplierArticle.supplier_ean' => 'nullable|string|max:255',
            'newSupplierArticle.purchase_price' => 'nullable|numeric|min:0',
            'newSupplierArticle.currency' => 'required|string|max:3',
            'newSupplierArticle.minimum_order_quantity' => 'nullable|integer|min:1',
            'newSupplierArticle.delivery_time_days' => 'nullable|integer|min:0',
            'newSupplierArticle.notes' => 'nullable|string',
            'newSupplierArticle.is_active' => 'boolean',
        ];

        $this->validate($rules);

        $data = [
            'supplier_id' => $this->newSupplierArticle['supplier_id'],
            'article_id' => $this->article->id,
            'supplier_article_number' => $this->newSupplierArticle['supplier_article_number'] ?: null,
            'supplier_ean' => $this->newSupplierArticle['supplier_ean'] ?: null,
            'purchase_price' => $this->newSupplierArticle['purchase_price'] ?: null,
            'currency' => $this->newSupplierArticle['currency'],
            'minimum_order_quantity' => $this->newSupplierArticle['minimum_order_quantity'] ?: null,
            'delivery_time_days' => $this->newSupplierArticle['delivery_time_days'] ?: null,
            'notes' => $this->newSupplierArticle['notes'] ?: null,
            'is_active' => $this->newSupplierArticle['is_active'],
            'team_id' => auth()->user()->currentTeam->id,
            'created_by_user_id' => auth()->id(),
            'owned_by_user_id' => auth()->id(),
        ];

        if ($this->editingSupplierArticleId) {
            $supplierArticle = \Platform\FoodService\Models\FsSupplierArticle::find($this->editingSupplierArticleId);
            $supplierArticle->update($data);
            $message = 'Lieferanten-Artikel erfolgreich aktualisiert!';
        } else {
            \Platform\FoodService\Models\FsSupplierArticle::create($data);
            $message = 'Lieferanten-Artikel erfolgreich erstellt!';
        }

        $this->resetSupplierArticleForm();
        $this->closeModal();
        session()->flash('message', $message);
    }

    public function openEditModal($supplierArticleId)
    {
        $supplierArticle = \Platform\FoodService\Models\FsSupplierArticle::find($supplierArticleId);
        if ($supplierArticle) {
            $this->editSupplierArticle = [
                'supplier_id' => $supplierArticle->supplier_id,
                'supplier_article_number' => $supplierArticle->supplier_article_number,
                'supplier_ean' => $supplierArticle->supplier_ean,
                'purchase_price' => $supplierArticle->purchase_price,
                'currency' => $supplierArticle->currency,
                'minimum_order_quantity' => $supplierArticle->minimum_order_quantity,
                'delivery_time_days' => $supplierArticle->delivery_time_days,
                'notes' => $supplierArticle->notes,
                'is_active' => $supplierArticle->is_active,
            ];
            $this->editingSupplierArticleId = $supplierArticleId;
            $this->editModalShow = true;
        }
    }

    public function closeEditModal()
    {
        $this->editModalShow = false;
        $this->editingSupplierArticleId = null;
        $this->resetEditSupplierArticleForm();
    }

    public function updateSupplierArticle()
    {
        $this->validate([
            'editSupplierArticle.supplier_id' => 'required|exists:fs_suppliers,id',
            'editSupplierArticle.supplier_article_number' => 'nullable|string|max:255',
            'editSupplierArticle.supplier_ean' => 'nullable|string|max:255',
            'editSupplierArticle.purchase_price' => 'nullable|numeric|min:0',
            'editSupplierArticle.currency' => 'required|string|max:3',
            'editSupplierArticle.minimum_order_quantity' => 'nullable|integer|min:1',
            'editSupplierArticle.delivery_time_days' => 'nullable|integer|min:0',
            'editSupplierArticle.notes' => 'nullable|string',
            'editSupplierArticle.is_active' => 'boolean',
        ]);

        $supplierArticle = \Platform\FoodService\Models\FsSupplierArticle::find($this->editingSupplierArticleId);
        if ($supplierArticle) {
            $supplierArticle->update([
                'supplier_id' => $this->editSupplierArticle['supplier_id'],
                'supplier_article_number' => $this->editSupplierArticle['supplier_article_number'] ?: null,
                'supplier_ean' => $this->editSupplierArticle['supplier_ean'] ?: null,
                'purchase_price' => $this->editSupplierArticle['purchase_price'] ?: null,
                'currency' => $this->editSupplierArticle['currency'],
                'minimum_order_quantity' => $this->editSupplierArticle['minimum_order_quantity'] ?: null,
                'delivery_time_days' => $this->editSupplierArticle['delivery_time_days'] ?: null,
                'notes' => $this->editSupplierArticle['notes'] ?: null,
                'is_active' => $this->editSupplierArticle['is_active'],
            ]);

            $this->closeEditModal();
            session()->flash('message', 'Lieferanten-Artikel erfolgreich aktualisiert!');
        }
    }

    private function resetEditSupplierArticleForm()
    {
        $this->editSupplierArticle = [
            'supplier_id' => '',
            'supplier_article_number' => '',
            'supplier_ean' => '',
            'purchase_price' => '',
            'currency' => 'EUR',
            'minimum_order_quantity' => '',
            'delivery_time_days' => '',
            'notes' => '',
            'is_active' => true,
        ];
    }

    public function deleteSupplierArticle($supplierArticleId)
    {
        $supplierArticle = \Platform\FoodService\Models\FsSupplierArticle::find($supplierArticleId);
        if ($supplierArticle) {
            $supplierArticle->delete();
            session()->flash('message', 'Lieferanten-Artikel erfolgreich gelöscht!');
        }
    }

    private function resetSupplierArticleForm()
    {
        $this->newSupplierArticle = [
            'supplier_id' => '',
            'supplier_article_number' => '',
            'supplier_ean' => '',
            'purchase_price' => '',
            'currency' => 'EUR',
            'minimum_order_quantity' => '',
            'delivery_time_days' => '',
            'notes' => '',
            'is_active' => true,
        ];
        $this->editingSupplierArticleId = null;
    }

    public function render()
    {
        return view('foodservice::livewire.article.show')
            ->layout('platform::layouts.app');
    }
}
