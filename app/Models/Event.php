<?php

namespace App\Models;

use App\Interfaces\ReportableModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\Reportable;
use Carbon\Carbon;

/**
 * 
 *
 * @property int $id
 * @property string $title
 * @property string $status unavailable | ongoing | ended
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $from
 * @property \Illuminate\Support\Carbon|null $to
 * @property int|null $user_id Creator of the event
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Assembly> $assemblies
 * @property-read int|null $assemblies_count
 * @property-read \App\Models\User|null $user
 * @method static \Database\Factories\EventFactory factory($count = null, $state = [])
 * @method static Builder|Event filter(array $filters)
 * @method static Builder|Event newModelQuery()
 * @method static Builder|Event newQuery()
 * @method static Builder|Event query()
 * @method static Builder|Event whereCreatedAt($value)
 * @method static Builder|Event whereDescription($value)
 * @method static Builder|Event whereFrom($value)
 * @method static Builder|Event whereId($value)
 * @method static Builder|Event whereStatus($value)
 * @method static Builder|Event whereTitle($value)
 * @method static Builder|Event whereTo($value)
 * @method static Builder|Event whereUpdatedAt($value)
 * @method static Builder|Event whereUserId($value)
 * @mixin \Eloquent
 */
class Event extends Model implements ReportableModel
{
    use HasFactory;
    use Reportable;

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'from' => 'datetime',
        'to' => 'datetime',
    ];

    protected $with = ['assemblies', 'user'];

    protected $guarded = ['id'];

    // Existing relationships remain the same
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assemblies(): BelongsToMany
    {
        return $this->belongsToMany(Assembly::class)->withTimestamps();
    }

    // Existing filter scope remains the same
    public function scopeFilter(Builder $query, array $filters)
    {
        $query
            ->when($filters["search"] ?? false, static function ($query, $search) {
                $query
                    ->where(static function ($query) use ($search) {
                        $query
                            ->where("title", "like", '%' . $search . '%')
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
            'title' => [
                'title' => 'Titre de l\'événement',
                'data' => 'title',
            ],
            'creator' => [
                'title' => 'Créé par',
                'data' => function ($event) {
                    return $event->user
                        ? $event->user->name . ' ' . ($event->user->surname ?? '')
                        : 'Créateur inconnu';
                },
            ],
            'description' => [
                'title' => 'Description',
                'data' => function ($event) {
                    return $event->description ?? 'Aucune description';
                },
            ],
            'status' => [
                'title' => 'Statut',
                'data' => function ($event) {
                    return match ($event->status) {
                        'unavailable' => 'Non disponible',
                        'ongoing' => 'En cours',
                        'ended' => 'Terminé',
                        default => ucfirst($event->status)
                    };
                },
            ],
            'date_range' => [
                'title' => 'Période',
                'data' => function ($event) {
                    // Handling different date scenario
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
                'title' => 'Assemblées concernées',
                'data' => function ($event) {
                    return $event->assemblies->isNotEmpty()
                        ? $event->assemblies->pluck('name')->implode(', ')
                        : 'Aucune assemblée';
                },
            ],
            'duration' => [
                'title' => 'Durée',
                'data' => function ($event) {
                    if ($event->from && $event->to) {
                        $duration = $event->from->diffInDays($event->to);
                        return $duration > 0
                            ? $duration . ' jour(s)'
                            : 'Moins d\'une journée';
                    }
                    return 'Non calculable';
                },
            ],
            'created_at' => [
                'title' => 'Date de création',
                'data' => function ($event) {
                    return $event->created_at->format('d/m/Y H:i');
                },
            ],
            'days_until_event' => [
                'title' => 'Jours avant l\'événement',
                'data' => function ($event) {
                    if ($event->from && $event->from->isFuture()) {
                        return now()->diffInDays($event->from) . ' jour(s)';
                    }
                    return 'Événement passé ou sans date';
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
        return "Liste des événements";
    }

    /**
     * Get the default ordering for reports.
     *
     * @return string
     */
    public static function getReportDefaultOrder()
    {
        return 'from';
    }

    /**
     * Get the report query with eager loaded relationships.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function getReportQuery()
    {
        return self::with(['user', 'assemblies'])
            // Optional: Add any specific query constraints
            ->orderBy('from');
    }
}
