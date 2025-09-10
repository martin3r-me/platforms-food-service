<?php

namespace Platform\FoodService\Livewire\BaseUnit;

use Livewire\Component;
use Platform\FoodService\Models\FsBaseUnit;

class Index extends Component
{
    public bool $modalShow = false;
    public string $name = '';
    public string $short_name = '';
    public ?string $description = null;
    public float $conversion_factor = 1.0;
    public bool $is_base_unit = false;
    public int $decimal_places = 2;
    public bool $is_active = true;
    public ?int $parent_id = null;
    public ?int $selectedParentId = null;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'short_name' => ['required', 'string', 'max:10'],
            'description' => ['nullable', 'string'],
            'conversion_factor' => ['required', 'numeric', 'min:0.000001'],
            'is_base_unit' => ['boolean'],
            'decimal_places' => ['required', 'integer', 'min:0', 'max:6'],
            'is_active' => ['boolean'],
            'parent_id' => ['nullable', 'integer', 'exists:fs_base_units,id'],
        ];
    }

    public function openCreateModal(?int $parentId = null): void
    {
        $this->resetValidation();
        $this->reset(['name', 'short_name', 'description', 'conversion_factor', 'is_base_unit', 'decimal_places', 'is_active', 'parent_id']);
        $this->conversion_factor = 1.0;
        $this->decimal_places = 2;
        $this->is_active = true;
        $this->selectedParentId = $parentId;
        
        if ($parentId) {
            // Parent ausgewählt - Parent setzen
            $this->parent_id = $parentId;
        }
        
        $this->modalShow = true;
    }

    public function closeCreateModal(): void
    {
        $this->modalShow = false;
    }

    public function getParentOptionsProperty()
    {
        return FsBaseUnit::categories()->orderBy('name')->get();
    }

    public function createItem(): void
    {
        $this->validate();

        FsBaseUnit::create([
            'name' => $this->name,
            'short_name' => $this->short_name,
            'description' => $this->description,
            'conversion_factor' => $this->conversion_factor,
            'is_base_unit' => $this->is_base_unit,
            'decimal_places' => $this->decimal_places,
            'is_active' => $this->is_active,
            'parent_id' => $this->parent_id,
            'team_id' => auth()->user()->current_team_id ?? null,
            'created_by_user_id' => auth()->id() ?? null,
            'owned_by_user_id' => auth()->id() ?? null,
        ]);

        $this->closeCreateModal();
    }

    public function seedDefaultUnits(): void
    {
        // Prüfen ob bereits Daten vorhanden sind
        if (FsBaseUnit::count() > 0) {
            $this->addError('seed', 'Base units already exist. Please clear existing data first.');
            return;
        }

        try {
            // Seeder ausführen
            $seeder = new \Platform\FoodService\Database\Seeders\FsBaseUnitSeeder();
            $seeder->run();
            
            session()->flash('message', 'Default base units seeded successfully!');
        } catch (\Exception $e) {
            $this->addError('seed', 'Error seeding base units: ' . $e->getMessage());
        }
    }

    public function getTreeItemsProperty()
    {
        $allItems = FsBaseUnit::with(['parent'])->orderBy('name')->get();
        $tree = collect();
        
        // Erst alle Kategorien (ohne parent_id)
        $categories = $allItems->whereNull('parent_id');
        foreach ($categories as $category) {
            $tree->push($category);
            $this->addChildrenToTree($tree, $category, $allItems, 1);
        }
        
        return $tree;
    }
    
    private function addChildrenToTree($tree, $parent, $allItems, $level)
    {
        $children = $allItems->where('parent_id', $parent->id);
        foreach ($children as $child) {
            $child->level = $level;
            $tree->push($child);
            $this->addChildrenToTree($tree, $child, $allItems, $level + 1);
        }
    }

    public function render()
    {
        return view('foodservice::livewire.base-unit.index', [
            'items' => $this->treeItems,
        ])->layout('platform::layouts.app');
    }
}
