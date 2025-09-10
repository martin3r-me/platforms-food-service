<?php

namespace Platform\FoodService\Livewire\Manufacturer;

use Livewire\Component;
use Platform\FoodService\Models\FsManufacturer;

class Manufacturer extends Component
{
    public FsManufacturer $manufacturer;
    public bool $isDirty = false;
    public bool $settingsOpen = true;

    public function mount(FsManufacturer $manufacturer): void
    {
        $this->manufacturer = $manufacturer;
    }

    protected function rules(): array
    {
        return [
            'manufacturer.name' => ['required', 'string', 'max:255'],
            'manufacturer.description' => ['nullable', 'string'],
            'manufacturer.is_active' => ['boolean'],
        ];
    }

    public function updated($property): void
    {
        if (str_starts_with($property, 'manufacturer.')) {
            $this->isDirty = true;
        }
    }

    public function save(): void
    {
        $this->validate();
        $this->manufacturer->save();
        $this->isDirty = false;
    }

    public function deleteItem(): void
    {
        $this->manufacturer->delete();
        $this->redirectRoute('foodservice.manufacturers.index');
    }

    public function render()
    {
        return view('foodservice::livewire.manufacturer.manufacturer', [
            'manufacturer' => $this->manufacturer->load('brands'),
        ])->layout('platform::layouts.app');
    }
}
