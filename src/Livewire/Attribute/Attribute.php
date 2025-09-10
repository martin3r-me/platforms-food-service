<?php

namespace Platform\FoodService\Livewire\Attribute;

use Livewire\Component;
use Platform\FoodService\Models\FsAttribute;
use Illuminate\Validation\Rule;

class Attribute extends Component
{
    public FsAttribute $attribute;
    public bool $isDirty = false;
    public bool $settingsOpen = true;

    public function mount(FsAttribute $attribute): void
    {
        $this->attribute = $attribute;
    }

    protected function rules(): array
    {
        return [
            'attribute.name' => ['required', 'string', 'max:255'],
            'attribute.description' => ['nullable', 'string'],
            'attribute.is_strict' => ['boolean'],
            'attribute.is_active' => ['boolean'],
            'attribute.parent_id' => [
                'nullable',
                'integer',
                Rule::notIn([$this->attribute->id]),
                'exists:fs_attributes,id',
            ],
        ];
    }

    public function updated($property): void
    {
        if (str_starts_with($property, 'attribute.')) {
            $this->isDirty = true;
        }
    }

    public function save(): void
    {
        $this->validate();
        $this->attribute->save();
        $this->isDirty = false;
    }

    public function deleteItem(): void
    {
        $this->attribute->delete();
        $this->redirectRoute('foodservice.attributes.index');
    }

    public function render()
    {
        return view('foodservice::livewire.attribute.attribute', [
            'attribute' => $this->attribute,
        ])->layout('platform::layouts.app');
    }

    public function getParentOptionsProperty()
    {
        return FsAttribute::query()
            ->when($this->attribute?->id, fn ($q) => $q->where('id', '!=', $this->attribute->id))
            ->orderBy('name')
            ->get();
    }
}


