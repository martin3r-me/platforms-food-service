<?php

namespace Platform\FoodService\Livewire\Brand;

use Livewire\Component;
use Platform\FoodService\Models\FsBrand;
use Platform\FoodService\Models\FsManufacturer;

class Brand extends Component
{
    public FsBrand $brand;
    public bool $isDirty = false;
    public bool $settingsOpen = true;

    public function mount(FsBrand $brand): void
    {
        $this->brand = $brand->load('manufacturers');
    }

    protected function rules(): array
    {
        return [
            'brand.name' => ['required', 'string', 'max:255'],
            'brand.description' => ['nullable', 'string'],
            'brand.is_active' => ['boolean'],
        ];
    }

    public function updated($property): void
    {
        if (str_starts_with($property, 'brand.')) {
            $this->isDirty = true;
        }
    }

    public function save(): void
    {
        $this->validate();
        $this->brand->save();
        $this->isDirty = false;
    }

    public function deleteItem(): void
    {
        $this->brand->delete();
        $this->redirectRoute('foodservice.brands.index');
    }

    public function render()
    {
        $manufacturers = FsManufacturer::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('foodservice::livewire.brand.brand', [
            'brand' => $this->brand,
            'manufacturers' => $manufacturers,
        ])->layout('platform::layouts.app');
    }
}
