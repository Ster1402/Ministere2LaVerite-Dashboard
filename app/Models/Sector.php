<?php

namespace App\Models;

use App\Interfaces\ReportableModel;
use App\Interfaces\FilterableModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\Reportable;
use App\Traits\Filterable;

/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int|null $master_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Assembly> $assemblies
 * @property-read int|null $assemblies_count
 * @property-read Sector|null $master
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Sector> $subsectors
 * @property-read int|null $subsectors_count
 * @method static Builder|Sector applyFilters(array $filters)
 * @method static \Database\Factories\SectorFactory factory($count = null, $state = [])
 * @method static Builder|Sector filter(array $filters)
 * @method static Builder|Sector newModelQuery()
 * @method static Builder|Sector newQuery()
 * @method static Builder|Sector query()
 * @method static Builder|Sector whereCreatedAt($value)
 * @method static Builder|Sector whereDescription($value)
 * @method static Builder|Sector whereId($value)
 * @method static Builder|Sector whereMasterId($value)
 * @method static Builder|Sector whereName($value)
 * @method static Builder|Sector whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Sector extends Model implements ReportableModel, FilterableModel
{
    use HasFactory;
    use Reportable;
    use Filterable;

    protected $guarded = ['id'];
    protected $with = ['master'];

    /**
     * Relationship with master sector
     */
    public function master(): BelongsTo
    {
        return $this->belongsTo(Sector::class, 'master_id');
    }

    /**
     * Relationship with subsectors
     */
    public function subsectors(): HasMany
    {
        return $this->hasMany(Sector::class, 'master_id', 'id');
    }

    /**
     * Relationship with assemblies in this sector
     */
    public function assemblies(): HasMany
    {
        return $this->hasMany(Assembly::class);
    }

    /**
     * Scope for basic search filtering
     */
    public function scopeFilter(Builder $query, array $filters)
    {
        $query->when($filters["search"] ?? false, static function ($query, $search) {
            $query->where(static function ($query) use ($search) {
                $query
                    ->where("name", "like", '%' . $search . '%')
                    ->orWhere("description", "like", '%' . $search . '%')
                    ->orWhereHas('master', fn($q) => $q->where('name', 'like', '%' . $search . '%'));
            });
        });
    }

    /**
     * Get custom filterable attributes for this model
     */
    public static function getCustomFilterableAttributes(): array
    {
        return [
            'master_name' => [
                'name' => 'master_name',
                'display_name' => 'Nom du secteur parent',
                'type' => 'string',
                'relation' => 'master',
                'relation_column' => 'name',
                'operators' => ['equals', 'not_equals', 'contains'],
            ],
            'subsectors_count' => [
                'name' => 'subsectors_count',
                'display_name' => 'Nombre de sous-secteurs',
                'type' => 'integer',
                'relation' => 'subsectors',
                'relation_column' => 'id',
                'count' => true,
                'operators' => ['equals', 'not_equals', 'greater_than', 'less_than', 'greater_than_or_equal', 'less_than_or_equal'],
            ],
            'assemblies_count' => [
                'name' => 'assemblies_count',
                'display_name' => 'Nombre d\'assemblées',
                'type' => 'integer',
                'relation' => 'assemblies',
                'relation_column' => 'id',
                'count' => true,
                'operators' => ['equals', 'not_equals', 'greater_than', 'less_than', 'greater_than_or_equal', 'less_than_or_equal'],
            ],
            'has_subsectors' => [
                'name' => 'has_subsectors',
                'display_name' => 'A des sous-secteurs',
                'type' => 'boolean',
                'relation' => 'subsectors',
                'exists' => true,
                'operators' => ['equals'],
            ],
            'has_assemblies' => [
                'name' => 'has_assemblies',
                'display_name' => 'A des assemblées',
                'type' => 'boolean',
                'relation' => 'assemblies',
                'exists' => true,
                'operators' => ['equals'],
            ],
        ];
    }

    /**
     * Get the report query with eager loaded relationships.
     */
    public static function getReportQuery()
    {
        return self::with(['master'])
            ->withCount(['subsectors', 'assemblies']);
    }

    /**
     * Get reportable columns for this model.
     */
    public static function getReportableColumns()
    {
        return [
            'id' => [
                'title' => 'ID',
                'data' => 'id',
            ],
            'name' => [
                'title' => 'Nom du secteur',
                'data' => 'name',
            ],
            'master_sector' => [
                'title' => 'Secteur parent',
                'data' => function ($sector) {
                    return $sector->master ? $sector->master->name : 'Aucun';
                },
            ],
            'description' => [
                'title' => 'Description',
                'data' => function ($sector) {
                    return $sector->description ?? 'Non renseigné';
                },
            ],
            'subsectors_count' => [
                'title' => 'Nombre de sous-secteurs',
                'data' => function ($sector) {
                    return $sector->subsectors_count ?? 0;
                },
            ],
            'assemblies_count' => [
                'title' => 'Nombre d\'assemblées',
                'data' => function ($sector) {
                    return $sector->assemblies_count ?? 0;
                },
            ],
            'created_at' => [
                'title' => 'Date de création',
                'data' => function ($sector) {
                    return $sector->created_at->format('d/m/Y H:i');
                },
            ],
            'updated_at' => [
                'title' => 'Dernière mise à jour',
                'data' => function ($sector) {
                    return $sector->updated_at->format('d/m/Y H:i');
                },
            ],
        ];
    }

    /**
     * Get the report title.
     */
    public static function getReportTitle()
    {
        return "Liste des secteurs";
    }

    /**
     * Get the default ordering for reports.
     */
    public static function getReportDefaultOrder()
    {
        return 'name';
    }
}
