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
            // Skip columns that shouldn't be filtered
            if (in_array($column, [
                'password',
                'remember_token',
                'two_factor_secret',
                'two_factor_recovery_codes',
                'profile_photo_path',
                'current_team_id'
            ])) {
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
                'type' => self::getMappedType($type),
                'operators' => self::getOperatorsForType($type),
            ];
        }

        // Add custom model-specific attributes if method exists
        if (method_exists(static::class, 'getCustomFilterableAttributes')) {
            $customAttributes = static::getCustomFilterableAttributes();
            $result = array_merge($result, $customAttributes);
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
                    'display' => 'Égal à',
                    'operator' => '=',
                ],
                'not_equals' => [
                    'display' => 'Différent de',
                    'operator' => '!=',
                ],
                'contains' => [
                    'display' => 'Contient',
                    'operator' => 'LIKE',
                    'value_modifier' => function ($value) {
                        return '%' . $value . '%';
                    },
                ],
                'starts_with' => [
                    'display' => 'Commence par',
                    'operator' => 'LIKE',
                    'value_modifier' => function ($value) {
                        return $value . '%';
                    },
                ],
                'ends_with' => [
                    'display' => 'Finit par',
                    'operator' => 'LIKE',
                    'value_modifier' => function ($value) {
                        return '%' . $value;
                    },
                ],
                'is_null' => [
                    'display' => 'Est vide',
                    'operator' => 'IS',
                    'value' => null,
                ],
                'is_not_null' => [
                    'display' => 'N\'est pas vide',
                    'operator' => 'IS NOT',
                    'value' => null,
                ],
            ],
            'integer' => [
                'equals' => [
                    'display' => 'Égal à',
                    'operator' => '=',
                ],
                'not_equals' => [
                    'display' => 'Différent de',
                    'operator' => '!=',
                ],
                'greater_than' => [
                    'display' => 'Supérieur à',
                    'operator' => '>',
                ],
                'greater_than_or_equal' => [
                    'display' => 'Supérieur ou égal à',
                    'operator' => '>=',
                ],
                'less_than' => [
                    'display' => 'Inférieur à',
                    'operator' => '<',
                ],
                'less_than_or_equal' => [
                    'display' => 'Inférieur ou égal à',
                    'operator' => '<=',
                ],
                'is_null' => [
                    'display' => 'Est vide',
                    'operator' => 'IS',
                    'value' => null,
                ],
                'is_not_null' => [
                    'display' => 'N\'est pas vide',
                    'operator' => 'IS NOT',
                    'value' => null,
                ],
            ],
            'boolean' => [
                'equals' => [
                    'display' => 'Est vrai',
                    'operator' => '=',
                    'value_modifier' => function ($value) {
                        return $value == '1' || $value == 'true' || $value === true ? 1 : 0;
                    },
                ],
                'not_equals' => [
                    'display' => 'Est faux',
                    'operator' => '=',
                    'value_modifier' => function ($value) {
                        return $value == '1' || $value == 'true' || $value === true ? 0 : 1;
                    },
                ],
            ],
            'datetime' => [
                'equals' => [
                    'display' => 'Égal à',
                    'operator' => '=',
                    'value_modifier' => function ($value) {
                        return date('Y-m-d', strtotime($value));
                    },
                ],
                'not_equals' => [
                    'display' => 'Différent de',
                    'operator' => '!=',
                    'value_modifier' => function ($value) {
                        return date('Y-m-d', strtotime($value));
                    },
                ],
                'greater_than' => [
                    'display' => 'Après',
                    'operator' => '>',
                    'value_modifier' => function ($value) {
                        return date('Y-m-d', strtotime($value));
                    },
                ],
                'less_than' => [
                    'display' => 'Avant',
                    'operator' => '<',
                    'value_modifier' => function ($value) {
                        return date('Y-m-d', strtotime($value));
                    },
                ],
                'is_null' => [
                    'display' => 'Est vide',
                    'operator' => 'IS',
                    'value' => null,
                ],
                'is_not_null' => [
                    'display' => 'N\'est pas vide',
                    'operator' => 'IS NOT',
                    'value' => null,
                ],
            ],
            'date' => [
                'equals' => [
                    'display' => 'Égal à',
                    'operator' => '=',
                    'value_modifier' => function ($value) {
                        return date('Y-m-d', strtotime($value));
                    },
                ],
                'not_equals' => [
                    'display' => 'Différent de',
                    'operator' => '!=',
                    'value_modifier' => function ($value) {
                        return date('Y-m-d', strtotime($value));
                    },
                ],
                'greater_than' => [
                    'display' => 'Après',
                    'operator' => '>',
                    'value_modifier' => function ($value) {
                        return date('Y-m-d', strtotime($value));
                    },
                ],
                'less_than' => [
                    'display' => 'Avant',
                    'operator' => '<',
                    'value_modifier' => function ($value) {
                        return date('Y-m-d', strtotime($value));
                    },
                ],
                'is_null' => [
                    'display' => 'Est vide',
                    'operator' => 'IS',
                    'value' => null,
                ],
                'is_not_null' => [
                    'display' => 'N\'est pas vide',
                    'operator' => 'IS NOT',
                    'value' => null,
                ],
            ],
            'default' => [
                'equals' => [
                    'display' => 'Égal à',
                    'operator' => '=',
                ],
                'not_equals' => [
                    'display' => 'Différent de',
                    'operator' => '!=',
                ],
                'is_null' => [
                    'display' => 'Est vide',
                    'operator' => 'IS',
                    'value' => null,
                ],
                'is_not_null' => [
                    'display' => 'N\'est pas vide',
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
        $customAttributes = method_exists(static::class, 'getCustomFilterableAttributes')
            ? static::getCustomFilterableAttributes()
            : [];

        foreach ($filters as $filter) {
            if (
                !isset($filter['field'], $filter['operator'], $filter['value']) &&
                !in_array($filter['operator'], ['is_null', 'is_not_null'])
            ) {
                continue;
            }

            $field = $filter['field'];
            $operatorKey = $filter['operator'];
            $value = $filter['value'] ?? null;

            // Handle custom relationship fields
            if (isset($customAttributes[$field])) {
                $customAttr = $customAttributes[$field];

                if (isset($customAttr['relation']) && isset($customAttr['relation_column'])) {
                    self::applyRelationFilter(
                        $query,
                        $customAttr['relation'],
                        $customAttr['relation_column'],
                        $operatorKey,
                        $value,
                        $customAttr['type']
                    );
                    continue;
                }
            }

            // Get the field type and operators
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
     * Apply filter to a relationship
     *
     * @param Builder $query
     * @param string $relation
     * @param string $column
     * @param string $operatorKey
     * @param mixed $value
     * @param string $type
     */
    protected static function applyRelationFilter(
        Builder $query,
        string $relation,
        string $column,
        string $operatorKey,
        $value = null,
        string $type = 'string'
    ) {
        $operators = self::getFilterOperators();
        $mappedType = self::getMappedType($type);

        // Get operator details
        if (!isset($operators[$mappedType][$operatorKey])) {
            return;
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

        // Handle different filtering scenarios
        if (isset($operatorDetails['value']) && $operatorDetails['value'] === null) {
            // Null checks
            $query->whereHas($relation, function ($q) use ($column, $sqlOperator) {
                $method = $sqlOperator === 'IS' ? 'whereNull' : 'whereNotNull';
                $q->$method($column);
            });
        } elseif (in_array($sqlOperator, ['LIKE', '=', '!=', '>', '<', '>=', '<='])) {
            // Standard comparison operators
            $query->whereHas($relation, function ($q) use ($column, $sqlOperator, $value) {
                $q->where($column, $sqlOperator, $value);
            });
        }
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
