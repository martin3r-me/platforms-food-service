<?php

namespace Platform\FoodService\Livewire\Brand;

use Livewire\Component;
use Platform\FoodService\Models\FsBrand;
use Platform\FoodService\Models\FsManufacturer;

class Index extends Component
{
    public bool $modalShow = false;
    public string $name = '';
    public ?string $description = null;
    public bool $is_active = true;
    public array $selectedManufacturers = [];
    public array $primaryManufacturer = [];

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ];
    }

    public function openCreateModal(): void
    {
        $this->resetValidation();
        $this->reset(['name', 'description', 'is_active', 'selectedManufacturers', 'primaryManufacturer']);
        $this->is_active = true;
        $this->modalShow = true;
    }

    public function closeCreateModal(): void
    {
        $this->modalShow = false;
    }

    public function createItem(): void
    {
        $this->validate();

        $brand = FsBrand::create([
            'name' => $this->name,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'team_id' => auth()->user()->current_team_id ?? null,
            'created_by_user_id' => auth()->id() ?? null,
            'owned_by_user_id' => auth()->id() ?? null,
        ]);

        // Attach manufacturers
        if (!empty($this->selectedManufacturers)) {
            $manufacturerData = [];
            foreach ($this->selectedManufacturers as $manufacturerId) {
                $manufacturerData[$manufacturerId] = [
                    'is_primary' => in_array($manufacturerId, $this->primaryManufacturer),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            $brand->manufacturers()->attach($manufacturerData);
        }

        $this->closeCreateModal();
    }

    public function render()
    {
        $items = FsBrand::query()
            ->orderBy('name')
            ->get();

        return view('foodservice::livewire.brand.index', [
            'items' => $items,
        ])->layout('platform::layouts.app');
    }
}