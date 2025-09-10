<?php

namespace Platform\FoodService\Livewire\Additive;

use Livewire\Component;
use Platform\FoodService\Models\FsAdditive;
use Illuminate\Validation\Rule;

class Additive extends Component
{
    public FsAdditive $additive;
    public bool $isDirty = false;
    public bool $settingsOpen = true;

    public function mount(FsAdditive $additive): void
    {
        $this->additive = $additive;
    }

    protected function rules(): array
    {
        return [
            'additive.name' => ['required', 'string', 'max:255'],
            'additive.description' => ['nullable', 'string'],
            'additive.is_strict' => ['boolean'],
            'additive.parent_id' => [
                'nullable',
                'integer',
                Rule::notIn([$this->additive->id]),
                'exists:fs_additives,id',
            ],
        ];
    }

    public function updated($property): void
    {
        if (str_starts_with($property, 'additive.')) {
            $this->isDirty = true;
        }
    }

    public function save(): void
    {
        $this->validate();
        $this->additive->save();
        $this->isDirty = false;
    }

    public function deleteItem(): void
    {
        $this->additive->delete();
        $this->redirectRoute('foodservice.additives.index');
    }

    public function render()
    {
        return view('foodservice::livewire.additive.additive', [
            'additive' => $this->additive,
        ])->layout('platform::layouts.app');
    }

    public function getParentOptionsProperty()
    {
        return FsAdditive::query()
            ->when($this->additive?->id, fn ($q) => $q->where('id', '!=', $this->additive->id))
            ->orderBy('name')
            ->get();
    }
}


