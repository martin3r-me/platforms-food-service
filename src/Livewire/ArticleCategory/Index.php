<?php

namespace Platform\FoodService\Livewire\ArticleCategory;

use Livewire\Component;
use Platform\FoodService\Models\FsArticleCategory;
use Platform\FoodService\Models\FsArticleCluster;

class Index extends Component
{
    public bool $modalShow = false;
    public string $name = '';
    public ?string $description = null;
    public bool $is_active = true;
    public ?int $cluster_id = null;
    public ?int $parent_id = null;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],
            'cluster_id' => ['required', 'integer', 'exists:fs_article_clusters,id'],
            'parent_id' => ['nullable', 'integer', 'exists:fs_article_categories,id'],
        ];
    }

    public function openCreateModal(): void
    {
        $this->resetValidation();
        $this->reset(['name', 'description', 'is_active', 'cluster_id', 'parent_id']);
        $this->is_active = true;
        
        // Wenn nur ein Cluster existiert, automatisch vorauswählen
        $clusters = FsArticleCluster::orderBy('name')->get();
        if ($clusters->count() === 1) {
            $this->cluster_id = $clusters->first()->id;
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
        $this->validate();

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
        $items = FsArticleCategory::with(['cluster','parent'])->orderBy('name')->get();
        $clusters = FsArticleCluster::orderBy('name')->get();

        return view('foodservice::livewire.article-category.index', [
            'items' => $items,
            'clusters' => $clusters,
        ])->layout('platform::layouts.app');
    }
}


