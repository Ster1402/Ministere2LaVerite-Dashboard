<?php

namespace App\Models;

use App\Interfaces\ReportableModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\Reportable;

class Sector extends Model implements ReportableModel
{
    use HasFactory;
    use Reportable;

    protected $guarded = ['id'];
    protected $with = ['master'];

    public function master(): BelongsTo
    {
        return $this->belongsTo(Sector::class, 'master_id');
    }

    public function subsectors(): HasMany
    {
        return $this->hasMany(Sector::class, 'master_id', 'id');
    }

    // Scopes filter (existing implementation)
    public function scopeFilter(Builder $query, array $filters)
    {
        $query
            ->when($filters["search"] ?? false, static function ($query, $search) {
                $query
                    ->where(static function ($query) use ($search) {
                        $query
                            ->where("name", "like", '%' . $search . '%');
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
                    return $sector->subsectors->count();
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
     *
     * @return string
     */
    public static function getReportTitle()
    {
        return "Liste des secteurs";
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
}
