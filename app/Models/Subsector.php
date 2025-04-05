<?php

namespace App\Models;

use App\Interfaces\FilterableModel;
use App\Interfaces\ReportableModel;
use App\Traits\Filterable;
use App\Traits\Reportable;
use Illuminate\Database\Eloquent\Builder;

/**
 *
 * @property-read \App\Models\Sector|null $master
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Sector> $subsectors
 * @property-read int|null $subsectors_count
 * @method static Builder|Subsector filter(array $filters)
 * @method static Builder|Subsector newModelQuery()
 * @method static Builder|Subsector newQuery()
 * @method static Builder|Subsector query()
 * @mixin \Eloquent
 */
class Subsector extends Sector implements ReportableModel, FilterableModel
{
    use Reportable;
    use Filterable;

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Enhanced scope for filtering subsectors.
     */
    public function scopeFilter(Builder $query, array $filters): void
    {
        // Start with the parent Sector's filter
        parent::scopeFilter($query, $filters);

        // Ensure only subsectors (with a master_id) are included
        $query->whereNotNull('master_id');

        // Additional subsector-specific filters
        $query
            // Filter by search term across name and description
            ->when($filters['search'] ?? false, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%');
                });
            })
            // Filter by exact name
            ->when($filters['name'] ?? false, function ($query, $name) {
                $query->where('name', $name);
            })
            // Filter by master sector ID
            ->when($filters['master_id'] ?? false, function ($query, $masterId) {
                $query->where('master_id', $masterId);
            })
            // Filter by master sector name (via relationship)
            ->when($filters['master_name'] ?? false, function ($query, $masterName) {
                $query->whereHas('master', function (Builder $query) use ($masterName) {
                    $query->where('name', 'like', '%' . $masterName . '%');
                });
            })
            // Filter by minimum number of assemblies
            ->when($filters['min_assemblies'] ?? false, function ($query, $count) {
                $query->whereHas('assemblies', function ($query) use ($count) {
                    $query->havingRaw('COUNT(*) >= ?', [$count]);
                });
            })
            // Filter by minimum number of subsectors
            ->when($filters['min_subsectors'] ?? false, function ($query, $count) {
                $query->has('subsectors', '>=', $count);
            })
            // Filter by hierarchy depth
            ->when($filters['depth'] ?? false, function ($query, $depth) {
                $query->where(function ($query) use ($depth) {
                    $query->whereRaw('(
                        WITH RECURSIVE sector_hierarchy AS (
                            SELECT id, master_id, 0 AS depth
                            FROM sectors
                            WHERE id = sectors.id
                            UNION ALL
                            SELECT s.id, s.master_id, sh.depth + 1
                            FROM sectors s
                            INNER JOIN sector_hierarchy sh ON s.id = sh.master_id
                        )
                        SELECT MAX(depth) FROM sector_hierarchy WHERE id = sectors.id
                    ) = ?', [$depth]);
                });
            })
            // Filter by creation date range (e.g., created_after)
            ->when($filters['created_after'] ?? false, function ($query, $date) {
                $query->where('created_at', '>=', $date);
            })
            // Filter by creation date range (e.g., created_before)
            ->when($filters['created_before'] ?? false, function ($query, $date) {
                $query->where('created_at', '<=', $date);
            });
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
                'display_name' => 'Nom',
                'type' => 'string',
                'operators' => ['equals', 'not_equals', 'contains', 'starts_with', 'ends_with'],
            ],
            'description' => [
                'name' => 'description',
                'display_name' => 'Description',
                'type' => 'string',
                'operators' => ['equals', 'not_equals', 'contains', 'is_null', 'is_not_null'],
            ],
            'master_id' => [
                'name' => 'master_id',
                'display_name' => 'Secteur parent (ID)',
                'type' => 'integer',
                'operators' => ['equals', 'not_equals'],
            ],
            'master_name' => [
                'name' => 'master_name',
                'display_name' => 'Nom du secteur parent',
                'type' => 'string',
                'operators' => ['equals', 'not_equals', 'contains'],
                'custom_query' => true,
            ],
            'assemblies_count' => [
                'name' => 'assemblies_count',
                'display_name' => 'Nombre d\'assemblées',
                'type' => 'integer',
                'operators' => ['equals', 'not_equals', 'greater_than', 'less_than', 'greater_than_or_equal', 'less_than_or_equal'],
            ],
            'subsectors_count' => [
                'name' => 'subsectors_count',
                'display_name' => 'Nombre de sous-secteurs',
                'type' => 'integer',
                'operators' => ['equals', 'not_equals', 'greater_than', 'less_than', 'greater_than_or_equal', 'less_than_or_equal'],
            ],
            'depth' => [
                'name' => 'depth',
                'display_name' => 'Profondeur hiérarchique',
                'type' => 'integer',
                'operators' => ['equals', 'not_equals', 'greater_than', 'less_than'],
                'custom_query' => true,
            ],
            'created_at' => [
                'name' => 'created_at',
                'display_name' => 'Date de création',
                'type' => 'datetime',
                'operators' => ['equals', 'not_equals', 'greater_than', 'less_than', 'is_null', 'is_not_null'],
            ],
            'updated_at' => [
                'name' => 'updated_at',
                'display_name' => 'Date de mise à jour',
                'type' => 'datetime',
                'operators' => ['equals', 'not_equals', 'greater_than', 'less_than', 'is_null', 'is_not_null'],
            ],
        ];
    }

    /**
     * Get reportable columns specifically for subsectors.
     *
     * @return array
     */
    public static function getReportableColumns(): array
    {
        return [
            'id' => [
                'title' => 'ID',
                'data' => 'id',
            ],
            'name' => [
                'title' => 'Nom du sous-secteur',
                'data' => 'name',
            ],
            'master_sector' => [
                'title' => 'Secteur parent',
                'data' => function ($subsector) {
                    return $subsector->master
                        ? $subsector->master->name
                        : 'Aucun secteur parent';
                },
            ],
            'description' => [
                'title' => 'Description',
                'data' => function ($subsector) {
                    return $subsector->description ?? 'Aucune description';
                },
            ],
            'assemblies_count' => [
                'title' => 'Nombre d\'assemblées',
                'data' => function ($subsector) {
                    return Assembly::where('sector_id', $subsector->id)->count();
                },
            ],
            'subsectors_depth' => [
                'title' => 'Profondeur hiérarchique',
                'data' => function ($subsector) {
                    $depth = 0;
                    $currentSector = $subsector;

                    while ($currentSector->master) {
                        $depth++;
                        $currentSector = $currentSector->master;
                    }

                    return $depth > 0
                        ? "Niveau {$depth}"
                        : 'Secteur principal';
                },
            ],
            'sister_subsectors' => [
                'title' => 'Sous-secteurs frères',
                'data' => function ($subsector) {
                    if (!$subsector->master) {
                        return 'N/A';
                    }

                    $sisterSubsectors = Sector::where('master_id', $subsector->master_id)
                        ->where('id', '!=', $subsector->id)
                        ->pluck('name');

                    return $sisterSubsectors->isNotEmpty()
                        ? $sisterSubsectors->implode(', ')
                        : 'Aucun sous-secteur frère';
                },
            ],
            'created_at' => [
                'title' => 'Date de création',
                'data' => function ($subsector) {
                    return $subsector->created_at->format('d/m/Y H:i');
                },
            ],
            'updated_at' => [
                'title' => 'Dernière mise à jour',
                'data' => function ($subsector) {
                    return $subsector->updated_at->format('d/m/Y H:i');
                },
            ],
        ];
    }

    /**
     * Get the report title.
     *
     * @return string
     */
    public static function getReportTitle(): string
    {
        return "Liste des sous-secteurs";
    }

    /**
     * Get the default ordering for reports.
     *
     * @return string
     */
    public static function getReportDefaultOrder(): string
    {
        return 'name';
    }

    /**
     * Get the report query with eager loaded relationships.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function getReportQuery(): Builder
    {
        return Sector::with(['master'])
            ->whereNotNull('master_id'); // Ensure only subsectors are retrieved
    }
}
