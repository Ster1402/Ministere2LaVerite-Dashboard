<?php

namespace App\Models;

use App\Interfaces\FilterableModel;
use App\Interfaces\ReportableModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
 * @property int $sector_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Event> $events
 * @property-read int|null $events_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Media> $medias
 * @property-read int|null $medias_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message> $messages
 * @property-read int|null $messages_count
 * @property-read \App\Models\Sector $sector
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Database\Factories\AssemblyFactory factory($count = null, $state = [])
 * @method static Builder|Assembly filter(array $filters)
 * @method static Builder|Assembly newModelQuery()
 * @method static Builder|Assembly newQuery()
 * @method static Builder|Assembly query()
 * @method static Builder|Assembly whereCreatedAt($value)
 * @method static Builder|Assembly whereDescription($value)
 * @method static Builder|Assembly whereId($value)
 * @method static Builder|Assembly whereName($value)
 * @method static Builder|Assembly whereSectorId($value)
 * @method static Builder|Assembly whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Assembly extends Model implements ReportableModel, FilterableModel
{
    use HasFactory;
    use Reportable;
    use Filterable;

    protected $guarded = ['id'];
    protected $with = ['sector'];

    // Existing relationships remain the same
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class)->withTimestamps();
    }

    public function messages(): BelongsToMany
    {
        return $this->belongsToMany(Message::class, 'assembly_messages');
    }

    public function sector(): BelongsTo
    {
        return $this->belongsTo(Sector::class);
    }

    public function medias(): BelongsToMany
    {
        return $this->belongsToMany(Media::class);
    }

    // Existing filter scope remains the same
    public function scopeFilter(Builder $query, array $filters)
    {
        $query
            ->when($filters["search"] ?? false, static function ($query, $search) {
                $query
                    ->where(static function ($query) use ($search) {
                        $query
                            ->where("name", "like", '%' . $search . '%')
                            ->orWhereHas('sector', fn($q) => $q->where('name', 'like', '%' . $search . '%'));
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
                'title' => 'Nom de l\'assemblée',
                'data' => 'name',
            ],
            'sector' => [
                'title' => 'Secteur',
                'data' => function ($assembly) {
                    return $assembly->sector ? $assembly->sector->name : 'Non assigné';
                },
            ],
            'description' => [
                'title' => 'Description',
                'data' => function ($assembly) {
                    return $assembly->description ?? 'Aucune description';
                },
            ],
            'users_count' => [
                'title' => 'Nombre de membres',
                'data' => function ($assembly) {
                    return $assembly->users_count ?? 0;
                },
            ],
            'events_count' => [
                'title' => 'Nombre d\'événements',
                'data' => function ($assembly) {
                    return $assembly->events_count ?? 0;
                },
            ],
            'messages_count' => [
                'title' => 'Nombre de messages',
                'data' => function ($assembly) {
                    return $assembly->messages_count ?? 0;
                },
            ],
            'medias_count' => [
                'title' => 'Nombre de médias',
                'data' => function ($assembly) {
                    return $assembly->medias_count ?? 0;
                },
            ],
            'created_at' => [
                'title' => 'Date de création',
                'data' => function ($assembly) {
                    return $assembly->created_at->format('d/m/Y H:i');
                },
            ],
            'updated_at' => [
                'title' => 'Dernière mise à jour',
                'data' => function ($assembly) {
                    return $assembly->updated_at->format('d/m/Y H:i');
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
        return "Liste des assemblées";
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
        return self::with(['sector'])
            ->withCount(['users', 'events', 'messages', 'medias']);
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
                'display_name' => 'Nom de l\'assemblée',
                'type' => 'string',
                'operators' => ['equals', 'not_equals', 'contains', 'starts_with', 'ends_with'],
            ],
            'description' => [
                'name' => 'description',
                'display_name' => 'Description',
                'type' => 'string',
                'operators' => ['equals', 'not_equals', 'contains', 'is_null', 'is_not_null'],
            ],
            'sector_id' => [
                'name' => 'sector_id',
                'display_name' => 'Secteur ID',
                'type' => 'integer',
                'operators' => ['equals', 'not_equals'],
            ],
            'sector_name' => [
                'name' => 'sector_name',
                'display_name' => 'Nom du secteur',
                'type' => 'string',
                'operators' => ['equals', 'not_equals', 'contains'],
                'custom_query' => true,
            ],
            'users_count' => [
                'name' => 'users_count',
                'display_name' => 'Nombre de membres',
                'type' => 'integer',
                'operators' => ['greater_than', 'less_than', 'equals'],
                'custom_query' => true,
            ],
            'events_count' => [
                'name' => 'events_count',
                'display_name' => 'Nombre d\'événements',
                'type' => 'integer',
                'operators' => ['greater_than', 'less_than', 'equals'],
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
                'display_name' => 'Dernière mise à jour',
                'type' => 'datetime',
                'operators' => ['greater_than', 'less_than'],
            ],
        ];
    }

    /**
     * Apply dynamic filters specific to this model.
     *
     * @param Builder $query
     * @param array $filters
     * @return Builder
     */
    public static function applyDynamicFilters(Builder $query, array $filters)
    {
        foreach ($filters as $filter) {
            if (!isset($filter['field'], $filter['operator'], $filter['value'])) {
                continue;
            }

            // Handle custom fields
            if ($filter['field'] === 'sector_name') {
                $query = self::applySectorNameFilter($query, $filter['operator'], $filter['value']);
                continue;
            }

            if ($filter['field'] === 'users_count') {
                $query = self::applyUsersCountFilter($query, $filter['operator'], $filter['value']);
                continue;
            }

            if ($filter['field'] === 'events_count') {
                $query = self::applyEventsCountFilter($query, $filter['operator'], $filter['value']);
                continue;
            }
        }

        // Apply standard filters from the Filterable trait
        return parent::applyDynamicFilters($query, $filters);
    }

    /**
     * Apply filter for sector name.
     *
     * @param Builder $query
     * @param string $operator
     * @param string $value
     * @return Builder
     */
    private static function applySectorNameFilter(Builder $query, string $operator, string $value): Builder
    {
        switch ($operator) {
            case 'equals':
                return $query->whereHas('sector', function ($q) use ($value) {
                    $q->where('name', $value);
                });
            case 'not_equals':
                return $query->whereHas('sector', function ($q) use ($value) {
                    $q->where('name', '!=', $value);
                });
            case 'contains':
                return $query->whereHas('sector', function ($q) use ($value) {
                    $q->where('name', 'like', '%' . $value . '%');
                });
            default:
                return $query;
        }
    }

    /**
     * Apply filter for users count.
     *
     * @param Builder $query
     * @param string $operator
     * @param int $value
     * @return Builder
     */
    private static function applyUsersCountFilter(Builder $query, string $operator, $value): Builder
    {
        $value = intval($value);

        // Use withCount to efficiently filter by the count
        switch ($operator) {
            case 'equals':
                return $query->withCount('users')->having('users_count', '=', $value);
            case 'greater_than':
                return $query->withCount('users')->having('users_count', '>', $value);
            case 'less_than':
                return $query->withCount('users')->having('users_count', '<', $value);
            default:
                return $query;
        }
    }

    /**
     * Apply filter for events count.
     *
     * @param Builder $query
     * @param string $operator
     * @param int $value
     * @return Builder
     */
    private static function applyEventsCountFilter(Builder $query, string $operator, $value): Builder
    {
        $value = intval($value);

        // Use withCount to efficiently filter by the count
        switch ($operator) {
            case 'equals':
                return $query->withCount('events')->having('events_count', '=', $value);
            case 'greater_than':
                return $query->withCount('events')->having('events_count', '>', $value);
            case 'less_than':
                return $query->withCount('events')->having('events_count', '<', $value);
            default:
                return $query;
        }
    }
}
