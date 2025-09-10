<?php

namespace Platform\FoodService\Livewire\BaseUnit;

use Livewire\Component;
use Platform\FoodService\Models\FsBaseUnit;
use Illuminate\Validation\Rule;

class BaseUnit extends Component
{
    public FsBaseUnit $baseUnit;
    public bool $isDirty = false;
    public bool $settingsOpen = true;

    public function mount(FsBaseUnit $baseUnit): void
    {
        $this->baseUnit = $baseUnit;
    }

    protected function rules(): array
    {
        return [
            'baseUnit.name' => ['required', 'string', 'max:255'],
            'baseUnit.short_name' => ['required', 'string', 'max:10'],
            'baseUnit.description' => ['nullable', 'string'],
            'baseUnit.conversion_factor' => ['required', 'numeric', 'min:0.000001'],
            'baseUnit.is_base_unit' => ['boolean'],
            'baseUnit.decimal_places' => ['required', 'integer', 'min:0', 'max:6'],
            'baseUnit.is_active' => ['boolean'],
            'baseUnit.parent_id' => [
                'nullable',
                'integer',
                Rule::notIn([$this->baseUnit->id]),
                'exists:fs_base_units,id',
            ],
        ];
    }

    public function updated($property): void
    {
        if (str_starts_with($property, 'baseUnit.')) {
            $this->isDirty = true;
        }
    }

    public function save(): void
    {
        $this->validate();
        $this->baseUnit->save();
        $this->isDirty = false;
    }

    public function deleteItem(): void
    {
        $this->baseUnit->delete();
        $this->redirectRoute('foodservice.base-units.index');
    }

    public function render()
    {
        return view('foodservice::livewire.base-unit.base-unit', [
            'baseUnit' => $this->baseUnit,
        ])->layout('platform::layouts.app');
    }

    public function getParentOptionsProperty()
    {
        return FsBaseUnit::categories()
            ->when($this->baseUnit?->id, fn ($q) => $q->where('id', '!=', $this->baseUnit->id))
            ->orderBy('name')
            ->get();
    }
}
