<?php

namespace Platform\FoodService\Livewire\ArticleCategory;

use Livewire\Component;
use Platform\FoodService\Models\FsArticleCategory;
use Platform\FoodService\Models\FsArticleCluster;
use Illuminate\Validation\Rule;

class ArticleCategory extends Component
{
    public FsArticleCategory $category;
    public bool $isDirty = false;
    public bool $modalShow = false;
    public string $name = '';
    public ?string $description = null;
    public bool $is_active = true;
    public ?int $cluster_id = null;
    public ?int $parent_id = null;
    public ?int $selectedParentId = null;

    protected function rules(): array
    {
        return [
            'category.name' => ['required', 'string', 'max:255'],
            'category.description' => ['nullable', 'string'],
            'category.is_active' => ['boolean'],
            'category.cluster_id' => ['required', 'integer', 'exists:fs_article_clusters,id'],
            'category.parent_id' => [
                'nullable', 'integer', 'exists:fs_article_categories,id',
                Rule::notIn([$this->category->id]),
            ],
            // Rules für Create-Modal
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],
            'cluster_id' => ['required', 'integer', 'exists:fs_article_clusters,id'],
            'parent_id' => ['nullable', 'integer', 'exists:fs_article_categories,id'],
        ];
    }

    public function mount(FsArticleCategory $category): void
    {
        $this->category = $category;
    }

    public function updated($prop): void
    {
        if (str_starts_with($prop, 'category.')) {
            $this->isDirty = true;
        }
    }

    public function save(): void
    {
        $this->validate();
        // parent muss gleiche cluster_id haben
        if ($this->category->parent_id) {
            $parent = FsArticleCategory::find($this->category->parent_id);
            if ($parent && $parent->cluster_id !== $this->category->cluster_id) {
                $this->addError('category.parent_id', 'Parent muss zur selben Cluster gehören.');
                return;
            }
        }

        $this->category->save();
        $this->isDirty = false;
    }

    public function getCategoryParentOptionsProperty()
    {
        return FsArticleCategory::where('cluster_id', $this->category->cluster_id)
            ->where('id', '!=', $this->category->id)
            ->orderBy('name')
            ->get();
    }

    public function getBreadcrumbsProperty()
    {
        $breadcrumbs = collect();
        $current = $this->category;
        
        // Alle Parents sammeln (von aktuell zu root)
        $parents = collect();
        while ($current->parent) {
            $parents->prepend($current->parent);
            $current = $current->parent;
        }
        
        return $parents;
    }

    public function getFullPathProperty()
    {
        $path = collect();
        $current = $this->category;
        
        // Alle Parents sammeln (von aktuell zu root)
        $parents = collect();
        while ($current->parent) {
            $parents->prepend($current->parent);
            $current = $current->parent;
        }
        
        // Vollständigen Pfad zusammenbauen
        $path = $parents->pluck('name');
        $path->push($this->category->name);
        
        return $path->join(' > ');
    }

    public function getChildrenTreeProperty()
    {
        $allChildren = FsArticleCategory::where('parent_id', $this->category->id)
            ->with(['cluster'])
            ->orderBy('name')
            ->get();
        
        $tree = collect();
        
        // Direkte Children sammeln
        foreach ($allChildren as $child) {
            $child->level = 0;
            $tree->push($child);
            $this->addChildrenToTree($tree, $child, 1);
        }
        
        return $tree;
    }
    
    private function addChildrenToTree($tree, $parent, $level)
    {
        $children = FsArticleCategory::where('parent_id', $parent->id)
            ->with(['cluster'])
            ->orderBy('name')
            ->get();
            
        foreach ($children as $child) {
            $child->level = $level;
            $tree->push($child);
            $this->addChildrenToTree($tree, $child, $level + 1);
        }
    }

    public function openCreateModal(?int $parentId = null): void
    {
        $this->resetValidation();
        $this->reset(['name', 'description', 'is_active', 'cluster_id', 'parent_id']);
        $this->is_active = true;
        $this->selectedParentId = $parentId;
        
        if ($parentId) {
            // Parent ausgewählt - Cluster und Parent setzen
            $parent = FsArticleCategory::find($parentId);
            if ($parent) {
                $this->cluster_id = $parent->cluster_id;
                $this->parent_id = $parentId;
            }
        } else {
            // Neuer Parent - Cluster automatisch vorauswählen wenn nur eins existiert
            $clusters = FsArticleCluster::orderBy('name')->get();
            if ($clusters->count() === 1) {
                $this->cluster_id = $clusters->first()->id;
            }
        }
        
        $this->modalShow = true;
    }

    public function closeCreateModal(): void
    {
        $this->modalShow = false;
    }

    public function getParentOptionsProperty()
    {
        if (!$this->cluster_id) return collect();
        return FsArticleCategory::where('cluster_id', $this->cluster_id)->orderBy('name')->get();
    }

    public function createItem(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],
            'cluster_id' => ['required', 'integer', 'exists:fs_article_clusters,id'],
            'parent_id' => ['nullable', 'integer', 'exists:fs_article_categories,id'],
        ]);

        // optional: parent muss zum selben Cluster gehören
        if ($this->parent_id) {
            $parent = FsArticleCategory::find($this->parent_id);
            if ($parent && $parent->cluster_id !== $this->cluster_id) {
                $this->addError('parent_id', 'Parent muss zur selben Cluster gehören.');
                return;
            }
        }

        FsArticleCategory::create([
            'name' => $this->name,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'cluster_id' => $this->cluster_id,
            'parent_id' => $this->parent_id,
            'team_id' => auth()->user()->current_team_id ?? null,
            'created_by_user_id' => auth()->id() ?? null,
            'owned_by_user_id' => auth()->id() ?? null,
        ]);

        $this->closeCreateModal();
    }

    public function render()
    {
        $clusters = FsArticleCluster::orderBy('name')->get();

        return view('foodservice::livewire.article-category.show', [
            'category' => $this->category,
            'clusters' => $clusters,
        ])->layout('platform::layouts.app');
    }
}


