<?php

namespace Platform\FoodService\Livewire\ArticleCluster;

use Livewire\Component;
use Platform\FoodService\Models\FsArticleCluster;

class ArticleCluster extends Component
{
    public FsArticleCluster $cluster;
    public bool $isDirty = false;

    protected function rules(): array
    {
        return [
            'cluster.name' => ['required', 'string', 'max:255'],
            'cluster.description' => ['nullable', 'string'],
            'cluster.is_active' => ['boolean'],
        ];
    }

    public function mount(FsArticleCluster $cluster): void
    {
        $this->cluster = $cluster;
    }

    public function updated($prop): void
    {
        if (str_starts_with($prop, 'cluster.')) {
            $this->isDirty = true;
        }
    }

    public function save(): void
    {
        $this->validate();
        $this->cluster->save();
        $this->isDirty = false;
    }

    public function deleteCluster(): void
    {
        $this->cluster->delete();
        $this->redirectRoute('foodservice.article-clusters.index');
    }

    public function render()
    {
        return view('foodservice::livewire.article-cluster.show', [
            'cluster' => $this->cluster,
        ])->layout('platform::layouts.app');
    }
}


