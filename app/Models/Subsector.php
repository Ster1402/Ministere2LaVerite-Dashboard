<?php

namespace App\Models;

use App\Interfaces\FilterableModel;
use App\Interfaces\ReportableModel;
use App\Traits\Reportable;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Builder;

/**
 *
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
     * Get reportable columns specifically for sub-sectors.
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
                    // Count assemblies in this subsector
                    return Assembly::where('sector_id', $subsector->id)->count();
                },
            ],
            'subsectors_depth' => [
                'title' => 'Profondeur hiérarchique',
                'data' => function ($subsector) {
                    // Calculate depth in sector hierarchy
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
                    // Find subsectors with the same master sector
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
    public static function getReportTitle()
    {
        return "Liste des sous-secteurs";
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
        return Sector::with(['master'])
            ->whereNotNull('master_id'); // Ensure only sub-sectors are retrieved
    }

    /**
     * Customize the base filter to focus on sub-sectors.
     *
     * @param Builder $query
     * @param array $filters
     * @return void
     */
    public function scopeFilter(Builder $query, array $filters)
    {
        // Start with the parent Sector's filter
        parent::scopeFilter($query, $filters);

        // Additional sub-sector specific filtering
        $query->whereNotNull('master_id');
    }
}
