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

    public function getParentOptionsProperty()
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

    public function render()
    {
        $clusters = FsArticleCluster::orderBy('name')->get();

        return view('foodservice::livewire.article-category.show', [
            'category' => $this->category,
            'clusters' => $clusters,
        ])->layout('platform::layouts.app');
    }
}


