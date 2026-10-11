<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Searchable
{
    public function scopeSearch(
        Builder $query,
        ?string $search,
        array $columns = []
    ): Builder {

        if (blank($search)) {
            return $query;
        }

        return $query->where(function ($query) use ($search, $columns) {

            foreach ($columns as $column) {
                $query->orWhere(
                    $column,
                    'LIKE',
                    '%' . $search . '%'
                );
            }

        });
    }
}