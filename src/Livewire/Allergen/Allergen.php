<?php

namespace Platform\FoodService\Livewire\Allergen;

use Livewire\Component;
use Platform\FoodService\Models\FsAllergen;
use Illuminate\Validation\Rule;

class Allergen extends Component
{
    public FsAllergen $allergen;
    public bool $isDirty = false;
    public bool $settingsOpen = true;

    public function mount(FsAllergen $allergen): void
    {
        $this->allergen = $allergen;
    }

    protected function rules(): array
    {
        return [
            'allergen.name' => ['required', 'string', 'max:255'],
            'allergen.description' => ['nullable', 'string'],
            'allergen.is_strict' => ['boolean'],
            'allergen.is_active' => ['boolean'],
            'allergen.parent_id' => [
                'nullable',
                'integer',
                Rule::notIn([$this->allergen->id]),
                'exists:fs_allergens,id',
            ],
        ];
    }

    public function updated($property): void
    {
        if (str_starts_with($property, 'allergen.')) {
            $this->isDirty = true;
        }
    }

    public function save(): void
    {
        $this->validate();
        $this->allergen->save();
        $this->isDirty = false;
    }

    public function deleteItem(): void
    {
        $this->allergen->delete();
        $this->redirectRoute('foodservice.allergens.index');
    }

    public function render()
    {
        return view('foodservice::livewire.allergen.allergen', [
            'allergen' => $this->allergen,
        ])->layout('platform::layouts.app');
    }

    public function getParentOptionsProperty()
    {
        return FsAllergen::query()
            ->when($this->allergen?->id, fn ($q) => $q->where('id', '!=', $this->allergen->id))
            ->orderBy('name')
            ->get();
    }
}


