<?php

namespace App\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;

trait Searchable
{
    #[Url]
    public string $search = '';

    /**
     * Reset pagination whenever the search term changes.
     */
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Apply a LIKE filter across the given columns, returning the builder unchanged when no search term is set.
     *
     * @param Builder<Model> $query
     * @param  array<int, string>  $columns
     * @return Builder<Model>
     */
    protected function applySearchFilter(Builder $query, array $columns): Builder
    {
        if ($this->search === '') {
            return $query;
        }

        return $query->where(function (Builder $q) use ($columns): void {
            foreach ($columns as $column) {
                $q->orWhere($column, 'like', '%' . $this->search . '%');
            }
        });
    }
}
