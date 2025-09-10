<?php

namespace Platform\FoodService\Livewire\Brand;

use Livewire\Component;
use Platform\FoodService\Models\FsBrand;

class Brand extends Component
{
    public FsBrand $brand;
    public bool $isDirty = false;

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
        return view('foodservice::livewire.brand.brand')->layout('platform::layouts.app');
    }
}