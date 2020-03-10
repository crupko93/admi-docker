<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait TablePaginate
{
    /**
     * Wrapper for filtering and paginating a model query using Vuetify data table pagination format
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @param Request $request            request containing filter / pagination data
     * @param integer $defaultRowsPerPage default rows per page when not provided in request
     * @param string  $defaultSortColumn  default column for initial sorting
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function scopeTablePaginate(
        $query,
        Request $request,
        $defaultRowsPerPage = 20,
        $defaultSortColumn  = 'id',
        $defaultSortOrder   = 'DESC'
    )
    {
        $rowsPerPage = $request->has('rowsPerPage') ? $request->rowsPerPage : $defaultRowsPerPage;
        $sortBy      = $request->has('sortBy') ? $request->sortBy : $defaultSortColumn;
        $sortOrder   = $request->has(['sortBy', 'descending'])
            ? ($request->descending === 'false' ? 'ASC' : 'DESC')
            : $defaultSortOrder;

        // Filters
        if ($request->has('filters')) {
            $request->filters = json_decode($request->filters, true);
            foreach ($request->filters as $column => $value) {
                if ($column === 'withTrashed') {
                    $query = $query->withTrashed();
                } else {
                    $query = is_array($value)
                        ? $query->whereIn($column, $value)
                        : $query->where($column, $value);
                }

            }
        }

        // Search
        if ($request->filled('search') && is_array($this->searchable)) {
            $search     = $request->search;
            $searchable = $this->searchable;

            $query = $query->where(function ($q) use ($search, $searchable) {
                foreach ($searchable as $column) {
                    $q = $q->orWhere($column, 'LIKE', sprintf('%%%s%%', $search));
                }

                if (is_array($this->searchable_relationships)) {
                    foreach ($this->searchable_relationships as $relationship => $columns) {
                        foreach ($columns as $column) {
                            $q = $q->orWhereHas($relationship, function ($q) use ($column, $search) {
                                $q->where($column, 'LIKE', sprintf('%%%s%%', $search));
                            });
                        }
                    }
                }

                return $q;
            });
        }

        // Sorting
        $query = $query->orderBy($sortBy, $sortOrder);

        // Pagination
        return $rowsPerPage ? $query->paginate($rowsPerPage) : $query->get();
    }
}
