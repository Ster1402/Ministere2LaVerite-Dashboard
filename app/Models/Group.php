<?php

namespace App\Models;

use App\Interfaces\FilterableModel;
use App\Interfaces\ReportableModel;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\Reportable;

/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Resource> $resources
 * @property-read int|null $resources_count
 * @method static \Database\Factories\GroupFactory factory($count = null, $state = [])
 * @method static Builder|Group filter(array $filters)
 * @method static Builder|Group newModelQuery()
 * @method static Builder|Group newQuery()
 * @method static Builder|Group query()
 * @method static Builder|Group whereCreatedAt($value)
 * @method static Builder|Group whereDescription($value)
 * @method static Builder|Group whereId($value)
 * @method static Builder|Group whereName($value)
 * @method static Builder|Group whereUpdatedAt($value)
 * @method static Builder|Group applyFilters(array $filters)
 * @mixin \Eloquent
 */
class Group extends Model implements ReportableModel, FilterableModel
{
    use HasFactory;
    use Reportable;
    use Filterable;

    protected $fillable = [
        'name',
        'description'
    ];

    /**
     * Relationship with resources
     */
    public function resources(): HasMany
    {
        return $this->hasMany(Resource::class);
    }

    /**
     * Scope for filtering groups
     */
    public function scopeFilter(Builder $query, array $filters)
    {
        $query
            ->when($filters["search"] ?? false, static function ($query, $search) {
                $query
                    ->where(static function ($query) use ($search) {
                        $query
                            ->where("name", "like", '%' . $search . '%')
                            ->orWhere("description", "like", '%' . $search . '%');
                    });
            });
    }

    /**
     * Get reportable columns for this model.
     *
     * @return array
     */
    public static function getReportableColumns()
    {
        return [
            'id' => [
                'title' => 'ID',
                'data' => 'id',
            ],
            'name' => [
                'title' => 'Nom du groupe',
                'data' => 'name',
            ],
            'description' => [
                'title' => 'Description',
                'data' => function ($group) {
                    return $group->description ?? 'Aucune description';
                },
            ],
            'resources_count' => [
                'title' => 'Nombre de ressources',
                'data' => function ($group) {
                    return $group->resources_count ?? 0;
                },
            ],
            'total_resource_quantity' => [
                'title' => 'Quantité totale de ressources',
                'data' => function ($group) {
                    return $group->resources->sum('quantity');
                },
            ],
            'resource_types' => [
                'title' => 'Types de ressources',
                'data' => function ($group) {
                    $resourceNames = $group->resources->pluck('name')->unique();
                    return $resourceNames->isNotEmpty()
                        ? $resourceNames->implode(', ')
                        : 'Aucune ressource';
                },
            ],
            'most_abundant_resource' => [
                'title' => 'Ressource la plus abondante',
                'data' => function ($group) {
                    $mostAbundantResource = $group->resources->sortByDesc('quantity')->first();
                    return $mostAbundantResource
                        ? $mostAbundantResource->name . ' (' . $mostAbundantResource->quantity . ')'
                        : 'N/A';
                },
            ],
            'created_at' => [
                'title' => 'Date de création',
                'data' => function ($group) {
                    return $group->created_at->format('d/m/Y H:i');
                },
            ],
            'updated_at' => [
                'title' => 'Dernière mise à jour',
                'data' => function ($group) {
                    return $group->updated_at->format('d/m/Y H:i');
                },
            ],
        ];
    }

    /**
     * Get the report title.
     *
     * @return string
     */
    public static function getReportTitle()
    {
        return "Liste des groupes";
    }

    /**
     * Get the default ordering for reports.
     *
     * @return string
     */
    public static function getReportDefaultOrder()
    {
        return 'name';
    }

    /**
     * Get the report query with eager loaded relationships.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function getReportQuery()
    {
        return self::with(['resources'])
            ->withCount('resources');
    }

    /**
     * Get the filterable attributes for this model.
     *
     * @return array
     */
    public static function getFilterableAttributes(): array
    {
        return [
            'id' => [
                'name' => 'id',
                'display_name' => 'ID',
                'type' => 'integer',
                'operators' => ['equals', 'not_equals', 'greater_than', 'less_than'],
            ],
            'name' => [
                'name' => 'name',
                'display_name' => 'Nom du groupe',
                'type' => 'string',
                'operators' => ['equals', 'not_equals', 'contains', 'starts_with', 'ends_with'],
            ],
            'description' => [
                'name' => 'description',
                'display_name' => 'Description',
                'type' => 'string',
                'operators' => ['equals', 'not_equals', 'contains', 'is_null', 'is_not_null'],
            ],
            'resources_count' => [
                'name' => 'resources_count',
                'display_name' => 'Nombre de ressources',
                'type' => 'integer',
                'operators' => ['equals', 'greater_than', 'less_than'],
                'custom_query' => true,
            ],
            'total_resource_quantity' => [
                'name' => 'total_resource_quantity',
                'display_name' => 'Quantité totale de ressources',
                'type' => 'integer',
                'operators' => ['greater_than', 'less_than'],
                'custom_query' => true,
            ],
            'created_at' => [
                'name' => 'created_at',
                'display_name' => 'Date de création',
                'type' => 'datetime',
                'operators' => ['greater_than', 'less_than'],
            ],
            'updated_at' => [
                'name' => 'updated_at',
                'display_name' => 'Date de mise à jour',
                'type' => 'datetime',
                'operators' => ['greater_than', 'less_than'],
            ],
        ];
    }

    /**
     * Apply dynamic filters specific to this model.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function applyDynamicFilters($query, array $filters)
    {
        foreach ($filters as $filter) {
            if (
                !isset($filter['field'], $filter['operator'], $filter['value']) &&
                !in_array($filter['operator'], ['is_null', 'is_not_null'])
            ) {
                continue;
            }

            // Handle custom filters
            if ($filter['field'] === 'resources_count') {
                $query = self::applyResourcesCountFilter($query, $filter['operator'], $filter['value']);
                continue;
            }

            if ($filter['field'] === 'total_resource_quantity') {
                $query = self::applyTotalResourceQuantityFilter($query, $filter['operator'], $filter['value']);
                continue;
            }
        }

        // Apply standard filters from the Filterable trait
        return parent::applyDynamicFilters($query, $filters);
    }

    /**
     * Apply filter for resources count.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $operator
     * @param int $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private static function applyResourcesCountFilter($query, string $operator, $value)
    {
        $value = intval($value);

        switch ($operator) {
            case 'equals':
                return $query->withCount('resources')->having('resources_count', '=', $value);
            case 'greater_than':
                return $query->withCount('resources')->having('resources_count', '>', $value);
            case 'less_than':
                return $query->withCount('resources')->having('resources_count', '<', $value);
            default:
                return $query;
        }
    }

    /**
     * Apply filter for total resource quantity.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $operator
     * @param int $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private static function applyTotalResourceQuantityFilter($query, string $operator, $value)
    {
        $value = intval($value);

        // We need to use a subquery here
        switch ($operator) {
            case 'greater_than':
                return $query->whereHas('resources', function ($q) use ($value) {
                    $q->selectRaw('SUM(quantity) as total_quantity')
                        ->groupBy('group_id')
                        ->havingRaw('SUM(quantity) > ?', [$value]);
                });
            case 'less_than':
                return $query->whereHas('resources', function ($q) use ($value) {
                    $q->selectRaw('SUM(quantity) as total_quantity')
                        ->groupBy('group_id')
                        ->havingRaw('SUM(quantity) < ?', [$value]);
                });
            default:
                return $query;
        }
    }
}
