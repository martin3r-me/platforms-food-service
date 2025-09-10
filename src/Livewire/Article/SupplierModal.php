<?php

namespace Platform\FoodService\Livewire\Article;

use Livewire\Component;
use Platform\FoodService\Models\FsArticle;
use Platform\FoodService\Models\FsSupplier;
use Platform\FoodService\Models\FsSupplierArticle;

class SupplierModal extends Component
{
    public FsArticle $article;
    public bool $modalShow = false;
    public ?FsSupplierArticle $supplierArticle = null;
    public bool $isEditing = false;

    // Form fields
    public $supplier_id = '';
    public $supplier_article_number = '';
    public $supplier_ean = '';
    public $purchase_price = '';
    public $currency = 'EUR';
    public $minimum_order_quantity = '';
    public $delivery_time_days = '';
    public $notes = '';
    public $is_active = true;

    protected $rules = [
        'supplier_id' => 'required|exists:fs_suppliers,id',
        'supplier_article_number' => 'nullable|string|max:255',
        'supplier_ean' => 'nullable|string|max:255',
        'purchase_price' => 'nullable|numeric|min:0',
        'currency' => 'required|string|max:3',
        'minimum_order_quantity' => 'nullable|integer|min:1',
        'delivery_time_days' => 'nullable|integer|min:0',
        'notes' => 'nullable|string',
        'is_active' => 'boolean',
    ];

    public function mount(FsArticle $article)
    {
        $this->article = $article;
    }

    public function openModal(?FsSupplierArticle $supplierArticle = null)
    {
        $this->supplierArticle = $supplierArticle;
        $this->isEditing = $supplierArticle !== null;
        
        if ($this->isEditing) {
            $this->supplier_id = $supplierArticle->supplier_id;
            $this->supplier_article_number = $supplierArticle->supplier_article_number;
            $this->supplier_ean = $supplierArticle->supplier_ean;
            $this->purchase_price = $supplierArticle->purchase_price;
            $this->currency = $supplierArticle->currency;
            $this->minimum_order_quantity = $supplierArticle->minimum_order_quantity;
            $this->delivery_time_days = $supplierArticle->delivery_time_days;
            $this->notes = $supplierArticle->notes;
            $this->is_active = $supplierArticle->is_active;
        } else {
            $this->resetForm();
        }
        
        $this->modalShow = true;
    }

    public function closeModal()
    {
        $this->modalShow = false;
        $this->resetForm();
        $this->supplierArticle = null;
        $this->isEditing = false;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'supplier_id' => $this->supplier_id,
            'article_id' => $this->article->id,
            'supplier_article_number' => $this->supplier_article_number ?: null,
            'supplier_ean' => $this->supplier_ean ?: null,
            'purchase_price' => $this->purchase_price ?: null,
            'currency' => $this->currency,
            'minimum_order_quantity' => $this->minimum_order_quantity ?: null,
            'delivery_time_days' => $this->delivery_time_days ?: null,
            'notes' => $this->notes ?: null,
            'is_active' => $this->is_active,
            'team_id' => auth()->user()->currentTeam->id,
            'created_by_user_id' => auth()->id(),
            'owned_by_user_id' => auth()->id(),
        ];

        if ($this->isEditing) {
            $this->supplierArticle->update($data);
            session()->flash('message', 'Lieferanten-Artikel erfolgreich aktualisiert!');
        } else {
            FsSupplierArticle::create($data);
            session()->flash('message', 'Lieferanten-Artikel erfolgreich erstellt!');
        }

        $this->closeModal();
        $this->dispatch('supplierArticleUpdated');
    }

    public function delete(FsSupplierArticle $supplierArticle)
    {
        $supplierArticle->delete();
        session()->flash('message', 'Lieferanten-Artikel erfolgreich gelöscht!');
        $this->dispatch('supplierArticleUpdated');
    }

    private function resetForm()
    {
        $this->supplier_id = '';
        $this->supplier_article_number = '';
        $this->supplier_ean = '';
        $this->purchase_price = '';
        $this->currency = 'EUR';
        $this->minimum_order_quantity = '';
        $this->delivery_time_days = '';
        $this->notes = '';
        $this->is_active = true;
    }

    public function getSuppliersProperty()
    {
        return FsSupplier::where('team_id', auth()->user()->currentTeam->id)
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

    public function render()
    {
        return view('food-service::livewire.article.supplier-modal');
    }
}
