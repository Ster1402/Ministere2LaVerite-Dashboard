<?php

namespace App\Models;

use App\Interfaces\ReportableModel;
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
 * @mixin \Eloquent
 */
class Group extends Model implements ReportableModel
{
    use HasFactory;
    use Reportable;

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
}
