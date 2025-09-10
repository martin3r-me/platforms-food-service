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
        $this->supplier = $supplier;
        
        // Settings Form initialisieren
        $this->settingsForm = [
            'description' => $this->supplier->description ?? '',
            'is_active' => $this->supplier->is_active,
        ];
    }

    public function rules(): array
    {
        return [
            'supplier.name' => 'required|string|max:255',
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
    public function stats()
    {
        return [
            'total_articles' => 0, // Placeholder - wird später implementiert
            'active_articles' => 0, // Placeholder - wird später implementiert
        ];
    }

    public function render()
    {
        return view('foodservice::livewire.supplier.show')
            ->layout('platform::layouts.app');
    }
}
