<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use ReflectionClass;

trait Filterable
{
    /**
     * Get the filterable attributes for this model.
     *
     * @return array
     */
    public static function getFilterableAttributes(): array
    {
        $model = new static;
        $table = $model->getTable();
        $columns = Schema::getColumnListing($table);

        $result = [];

        foreach ($columns as $column) {
            // Skip common columns that shouldn't be filtered
            if (in_array($column, ['password', 'remember_token', 'two_factor_secret', 'two_factor_recovery_codes'])) {
                continue;
            }

            $type = Schema::getColumnType($table, $column);

            // Check if there's a cast defined for this attribute
            $casts = $model->getCasts();
            if (isset($casts[$column])) {
                $type = $casts[$column];
            }

            // Determine a user-friendly display name
            $displayName = ucwords(str_replace('_', ' ', $column));

            $result[$column] = [
                'name' => $column,
                'display_name' => $displayName,
                'type' => $type,
                'operators' => self::getOperatorsForType($type),
            ];
        }

        return $result;
    }

    /**
     * Get default filter operators for different attribute types.
     *
     * @return array
     */
    public static function getFilterOperators(): array
    {
        return [
            'string' => [
                'equals' => [
                    'display' => 'Equals',
                    'operator' => '=',
                ],
                'not_equals' => [
                    'display' => 'Not Equals',
                    'operator' => '!=',
                ],
                'contains' => [
                    'display' => 'Contains',
                    'operator' => 'LIKE',
                    'value_modifier' => function ($value) {
                        return '%' . $value . '%';
                    },
                ],
                'starts_with' => [
                    'display' => 'Starts With',
                    'operator' => 'LIKE',
                    'value_modifier' => function ($value) {
                        return $value . '%';
                    },
                ],
                'ends_with' => [
                    'display' => 'Ends With',
                    'operator' => 'LIKE',
                    'value_modifier' => function ($value) {
                        return '%' . $value;
                    },
                ],
                'is_null' => [
                    'display' => 'Is Empty',
                    'operator' => 'IS',
                    'value' => null,
                ],
                'is_not_null' => [
                    'display' => 'Is Not Empty',
                    'operator' => 'IS NOT',
                    'value' => null,
                ],
            ],
            'integer' => [
                'equals' => [
                    'display' => 'Equals',
                    'operator' => '=',
                ],
                'not_equals' => [
                    'display' => 'Not Equals',
                    'operator' => '!=',
                ],
                'greater_than' => [
                    'display' => 'Greater Than',
                    'operator' => '>',
                ],
                'greater_than_or_equal' => [
                    'display' => 'Greater Than or Equal',
                    'operator' => '>=',
                ],
                'less_than' => [
                    'display' => 'Less Than',
                    'operator' => '<',
                ],
                'less_than_or_equal' => [
                    'display' => 'Less Than or Equal',
                    'operator' => '<=',
                ],
                'is_null' => [
                    'display' => 'Is Empty',
                    'operator' => 'IS',
                    'value' => null,
                ],
                'is_not_null' => [
                    'display' => 'Is Not Empty',
                    'operator' => 'IS NOT',
                    'value' => null,
                ],
            ],
            'boolean' => [
                'equals' => [
                    'display' => 'Equals',
                    'operator' => '=',
                ],
                'not_equals' => [
                    'display' => 'Not Equals',
                    'operator' => '!=',
                ],
            ],
            'datetime' => [
                'equals' => [
                    'display' => 'Equals',
                    'operator' => '=',
                    'value_modifier' => function ($value) {
                        return date('Y-m-d', strtotime($value));
                    },
                ],
                'not_equals' => [
                    'display' => 'Not Equals',
                    'operator' => '!=',
                    'value_modifier' => function ($value) {
                        return date('Y-m-d', strtotime($value));
                    },
                ],
                'greater_than' => [
                    'display' => 'After',
                    'operator' => '>',
                    'value_modifier' => function ($value) {
                        return date('Y-m-d', strtotime($value));
                    },
                ],
                'less_than' => [
                    'display' => 'Before',
                    'operator' => '<',
                    'value_modifier' => function ($value) {
                        return date('Y-m-d', strtotime($value));
                    },
                ],
                'is_null' => [
                    'display' => 'Is Empty',
                    'operator' => 'IS',
                    'value' => null,
                ],
                'is_not_null' => [
                    'display' => 'Is Not Empty',
                    'operator' => 'IS NOT',
                    'value' => null,
                ],
            ],
            'date' => [
                'equals' => [
                    'display' => 'Equals',
                    'operator' => '=',
                    'value_modifier' => function ($value) {
                        return date('Y-m-d', strtotime($value));
                    },
                ],
                'not_equals' => [
                    'display' => 'Not Equals',
                    'operator' => '!=',
                    'value_modifier' => function ($value) {
                        return date('Y-m-d', strtotime($value));
                    },
                ],
                'greater_than' => [
                    'display' => 'After',
                    'operator' => '>',
                    'value_modifier' => function ($value) {
                        return date('Y-m-d', strtotime($value));
                    },
                ],
                'less_than' => [
                    'display' => 'Before',
                    'operator' => '<',
                    'value_modifier' => function ($value) {
                        return date('Y-m-d', strtotime($value));
                    },
                ],
                'is_null' => [
                    'display' => 'Is Empty',
                    'operator' => 'IS',
                    'value' => null,
                ],
                'is_not_null' => [
                    'display' => 'Is Not Empty',
                    'operator' => 'IS NOT',
                    'value' => null,
                ],
            ],
            'default' => [
                'equals' => [
                    'display' => 'Equals',
                    'operator' => '=',
                ],
                'not_equals' => [
                    'display' => 'Not Equals',
                    'operator' => '!=',
                ],
                'is_null' => [
                    'display' => 'Is Empty',
                    'operator' => 'IS',
                    'value' => null,
                ],
                'is_not_null' => [
                    'display' => 'Is Not Empty',
                    'operator' => 'IS NOT',
                    'value' => null,
                ],
            ],
        ];
    }

    /**
     * Get operators for a specific attribute type.
     *
     * @param string $type
     * @return array
     */
    protected static function getOperatorsForType(string $type): array
    {
        $operators = self::getFilterOperators();

        // Map database types to our operator types
        $typeMap = [
            'varchar' => 'string',
            'char' => 'string',
            'text' => 'string',
            'longtext' => 'string',
            'int' => 'integer',
            'bigint' => 'integer',
            'tinyint' => 'integer',
            'smallint' => 'integer',
            'mediumint' => 'integer',
            'boolean' => 'boolean',
            'bool' => 'boolean',
            'date' => 'date',
            'datetime' => 'datetime',
            'timestamp' => 'datetime',
            // Add mappings for custom cast types
            'datetime' => 'datetime',
            'date' => 'date',
            'bool' => 'boolean',
            'boolean' => 'boolean',
            'int' => 'integer',
            'integer' => 'integer',
            'string' => 'string',
        ];

        $mappedType = $typeMap[$type] ?? 'default';
        return $operators[$mappedType] ?? $operators['default'];
    }

    /**
     * Apply dynamic filters to a query builder.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function applyDynamicFilters(Builder $query, array $filters)
    {
        if (empty($filters)) {
            return $query;
        }

        $operators = self::getFilterOperators();

        foreach ($filters as $filter) {
            if (!isset($filter['field'], $filter['operator'], $filter['value'])) {
                continue;
            }

            $field = $filter['field'];
            $operatorKey = $filter['operator'];
            $value = $filter['value'];

            // Get the field type
            $attributes = self::getFilterableAttributes();
            if (!isset($attributes[$field])) {
                continue;
            }

            $type = $attributes[$field]['type'];
            $mappedType = self::getMappedType($type);

            // Get operator details
            if (!isset($operators[$mappedType][$operatorKey])) {
                continue;
            }

            $operatorDetails = $operators[$mappedType][$operatorKey];
            $sqlOperator = $operatorDetails['operator'];

            // Apply value modifier if exists
            if (isset($operatorDetails['value_modifier']) && is_callable($operatorDetails['value_modifier'])) {
                $value = $operatorDetails['value_modifier']($value);
            }

            // If operator provides a value, use that instead
            if (array_key_exists('value', $operatorDetails)) {
                $value = $operatorDetails['value'];
            }

            // Apply the filter
            if ($sqlOperator === 'IS' || $sqlOperator === 'IS NOT') {
                $method = $sqlOperator === 'IS' ? 'whereNull' : 'whereNotNull';
                $query->$method($field);
            } else {
                $query->where($field, $sqlOperator, $value);
            }
        }

        return $query;
    }

    /**
     * Map a database or cast type to our operator types.
     *
     * @param string $type
     * @return string
     */
    protected static function getMappedType(string $type): string
    {
        $typeMap = [
            'varchar' => 'string',
            'char' => 'string',
            'text' => 'string',
            'longtext' => 'string',
            'int' => 'integer',
            'bigint' => 'integer',
            'tinyint' => 'integer',
            'smallint' => 'integer',
            'mediumint' => 'integer',
            'boolean' => 'boolean',
            'bool' => 'boolean',
            'date' => 'date',
            'datetime' => 'datetime',
            'timestamp' => 'datetime',
            // Add mappings for custom cast types
            'datetime' => 'datetime',
            'date' => 'date',
            'bool' => 'boolean',
            'boolean' => 'boolean',
            'int' => 'integer',
            'integer' => 'integer',
            'string' => 'string',
        ];

        return $typeMap[$type] ?? 'default';
    }

    /**
     * Scope a query to apply the dynamic filters.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeApplyFilters(Builder $query, array $filters)
    {
        return self::applyDynamicFilters($query, $filters);
    }
}
