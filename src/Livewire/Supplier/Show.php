<?php

namespace Platform\FoodService\Livewire\Supplier;

use Livewire\Component;
use Livewire\Attributes\Computed;
use Platform\FoodService\Models\FsSupplier;

class Show extends Component
{
    public FsSupplier $supplier;

    // Settings Modal
    public $settingsModalShow = false;
    
    // Settings Form
    public $settingsForm = [
        'description' => '',
        'is_active' => true,
    ];

    public function mount(FsSupplier $supplier)
    {
        $this->supplier = $supplier->load(['crmContactLinks.contact', 'crmCompanyLinks.company']);
        
        // Settings Form initialisieren
        $this->settingsForm = [
            'description' => $this->supplier->description ?? '',
            'is_active' => $this->supplier->is_active,
        ];
    }

    public function rules(): array
    {
        return [
            'supplier.supplier_number' => 'required|string|max:255|unique:fs_suppliers,supplier_number,' . $this->supplier->id,
            'settingsForm.description' => 'nullable|string',
            'settingsForm.is_active' => 'boolean',
        ];
    }

    public function save(): void
    {
        $this->validate();
        $this->supplier->save();

        session()->flash('message', 'Lieferant erfolgreich aktualisiert.');
    }

    public function saveSettings(): void
    {
        $this->validate([
            'settingsForm.description' => 'nullable|string',
            'settingsForm.is_active' => 'boolean',
        ]);

        $this->supplier->update([
            'description' => $this->settingsForm['description'],
            'is_active' => $this->settingsForm['is_active'],
        ]);

        $this->settingsModalShow = false;
        session()->flash('message', 'Einstellungen erfolgreich gespeichert.');
    }

    #[Computed]
    public function supplierArticles()
    {
        return $this->supplier->supplierArticles()
            ->with(['article'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    #[Computed]
    public function stats()
    {
        return [
            'total_articles' => $this->supplier->supplierArticles()->count(),
            'active_articles' => $this->supplier->supplierArticles()->where('is_active', true)->count(),
        ];
    }

    public function render()
    {
        return view('foodservice::livewire.supplier.show')
            ->layout('platform::layouts.app');
    }
}
