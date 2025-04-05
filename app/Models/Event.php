<?php

namespace App\Models;

use App\Interfaces\ReportableModel;
use App\Interfaces\FilterableModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\Reportable;
use App\Traits\Filterable;

class Event extends Model implements ReportableModel, FilterableModel
{
    use HasFactory;
    use Reportable;
    use Filterable;

    protected $guarded = ['id'];
    protected $with = ['user'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'from' => 'datetime',
        'to' => 'datetime',
    ];

    /**
     * Relationship with the event creator
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship with assemblies
     */
    public function assemblies(): BelongsToMany
    {
        return $this->belongsToMany(Assembly::class)->withTimestamps();
    }

    /**
     * Scope for basic search filtering
     */
    public function scopeFilter(Builder $query, array $filters)
    {
        $query->when($filters["search"] ?? false, static function ($query, $search) {
            $query->where(static function ($query) use ($search) {
                $query
                    ->where("title", "like", '%' . $search . '%')
                    ->orWhere("description", "like", '%' . $search . '%')
                    ->orWhereHas('user', fn($q) => $q->where('name', 'like', '%' . $search . '%'));
            });
        });
    }

    /**
     * Get custom filterable attributes for this model
     */
    public static function getCustomFilterableAttributes(): array
    {
        return [
            'creator_name' => [
                'name' => 'creator_name',
                'display_name' => 'Nom du créateur',
                'type' => 'string',
                'relation' => 'user',
                'relation_column' => 'name',
                'operators' => ['contains', 'equals', 'not_equals'],
            ],
            'assembly_id' => [
                'name' => 'assembly_id',
                'display_name' => 'ID de l\'assemblée',
                'type' => 'integer',
                'relation' => 'assemblies',
                'relation_column' => 'id',
                'operators' => ['equals', 'not_equals', 'is_null', 'is_not_null'],
            ],
            'assembly_name' => [
                'name' => 'assembly_name',
                'display_name' => 'Nom de l\'assemblée',
                'type' => 'string',
                'relation' => 'assemblies',
                'relation_column' => 'name',
                'operators' => ['contains', 'equals', 'not_equals'],
            ],
        ];
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
            'title' => [
                'title' => 'Titre',
                'data' => 'title',
            ],
            'status' => [
                'title' => 'Statut',
                'data' => function ($event) {
                    return match ($event->status) {
                        'unavailable' => 'Non disponible',
                        'ongoing' => 'En cours',
                        'ended' => 'Terminé',
                        default => $event->status
                    };
                },
            ],
            'creator' => [
                'title' => 'Créateur',
                'data' => function ($event) {
                    return $event->user
                        ? $event->user->name . ' ' . ($event->user->surname ?? '')
                        : 'Créateur inconnu';
                },
            ],
            'date_range' => [
                'title' => 'Période',
                'data' => function ($event) {
                    if (!$event->from && !$event->to) {
                        return 'Date non spécifiée';
                    }

                    if ($event->from && $event->to) {
                        return sprintf(
                            'Du %s au %s',
                            $event->from->format('d/m/Y'),
                            $event->to->format('d/m/Y')
                        );
                    }

                    if ($event->from) {
                        return 'À partir du ' . $event->from->format('d/m/Y');
                    }

                    return 'Jusqu\'au ' . $event->to->format('d/m/Y');
                },
            ],
            'assemblies' => [
                'title' => 'Assemblées',
                'data' => function ($event) {
                    return $event->assemblies->isNotEmpty()
                        ? $event->assemblies->pluck('name')->implode(', ')
                        : 'Aucune assemblée';
                },
            ],
            'created_at' => [
                'title' => 'Date de création',
                'data' => function ($event) {
                    return $event->created_at->format('d/m/Y H:i');
                },
            ],
            'updated_at' => [
                'title' => 'Dernière mise à jour',
                'data' => function ($event) {
                    return $event->updated_at->format('d/m/Y H:i');
                },
            ],
        ];
    }

    /**
     * Get the report title.
     */
    public static function getReportTitle()
    {
        return "Liste des événements";
    }

    /**
     * Get the default ordering for reports.
     */
    public static function getReportDefaultOrder()
    {
        return 'from';
    }

    /**
     * Get the report query with eager loaded relationships.
     */
    public static function getReportQuery()
    {
        return self::with(['user', 'assemblies'])
            ->orderBy('from');
    }
}
