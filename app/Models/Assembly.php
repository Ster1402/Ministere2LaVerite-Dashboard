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
     * Get custom filterable attributes for this model
     */
    public static function getCustomFilterableAttributes(): array
    {
        return [
            'sector_name' => [
                'name' => 'sector_name',
                'display_name' => 'Nom du secteur',
                'type' => 'string',
                'relation' => 'sector',
                'relation_column' => 'name',
                'operators' => ['equals', 'not_equals', 'contains'],
            ],
            'users_count' => [
                'name' => 'users_count',
                'display_name' => 'Nombre de membres',
                'type' => 'integer',
                'relation' => 'users',
                'relation_column' => 'id',
                'count' => true,
                'operators' => ['equals', 'not_equals', 'greater_than', 'less_than', 'greater_than_or_equal', 'less_than_or_equal'],
            ],
            'events_count' => [
                'name' => 'events_count',
                'display_name' => 'Nombre d\'événements',
                'type' => 'integer',
                'relation' => 'events',
                'relation_column' => 'id',
                'count' => true,
                'operators' => ['equals', 'not_equals', 'greater_than', 'less_than', 'greater_than_or_equal', 'less_than_or_equal'],
            ],
            'messages_count' => [
                'name' => 'messages_count',
                'display_name' => 'Nombre de messages',
                'type' => 'integer',
                'relation' => 'messages',
                'relation_column' => 'id',
                'count' => true,
                'operators' => ['equals', 'not_equals', 'greater_than', 'less_than', 'greater_than_or_equal', 'less_than_or_equal'],
            ],
            'medias_count' => [
                'name' => 'medias_count',
                'display_name' => 'Nombre de médias',
                'type' => 'integer',
                'relation' => 'medias',
                'relation_column' => 'id',
                'count' => true,
                'operators' => ['equals', 'not_equals', 'greater_than', 'less_than', 'greater_than_or_equal', 'less_than_or_equal'],
            ],
        ];
    }

    // Rest of the model remains the same as in the original implementation
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
     */
    public static function getReportTitle()
    {
        return "Liste des assemblées";
    }

    /**
     * Get the default ordering for reports.
     */
    public static function getReportDefaultOrder()
    {
        return 'name';
    }

    /**
     * Get the report query with eager loaded relationships.
     */
    public static function getReportQuery()
    {
        return self::with(['sector'])
            ->withCount(['users', 'events', 'messages', 'medias']);
    }
}
