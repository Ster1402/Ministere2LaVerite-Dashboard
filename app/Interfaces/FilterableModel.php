<?php

namespace App\Interfaces;

interface FilterableModel
{
    /**
     * Get the filterable attributes for this model.
     *
     * @return array
     */
    public static function getFilterableAttributes(): array;

    /**
     * Get the available filter operators for different attribute types.
     *
     * @return array
     */
    public static function getFilterOperators(): array;

    /**
     * Apply dynamic filters to a query builder.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function applyDynamicFilters($query, array $filters);
}
