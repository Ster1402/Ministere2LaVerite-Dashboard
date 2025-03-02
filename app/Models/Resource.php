<?php

namespace App\Models;

use App\Interfaces\ReportableModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\Reportable;

class Resource extends Model implements ReportableModel
{
    use HasFactory;
    use Reportable;

    protected $guarded = ['id'];
    protected $with = ['group'];

    // Existing relationships remain the same
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'borrowed')
            ->withPivot('borrowed_at', 'returned_at', 'quantity')
            ->withTimestamps();
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
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
                            ->orWhereHas('group', fn($q) => $q->where('name', 'like', '%' . $search . '%'));
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
                'title' => 'Nom de la ressource',
                'data' => 'name',
            ],
            'group' => [
                'title' => 'Groupe',
                'data' => function ($resource) {
                    return $resource->group ? $resource->group->name : 'Non assigné';
                },
            ],
            'description' => [
                'title' => 'Description',
                'data' => function ($resource) {
                    return $resource->description ?? 'Aucune description';
                },
            ],
            'total_quantity' => [
                'title' => 'Quantité totale',
                'data' => 'quantity',
            ],
            'borrowed_quantity' => [
                'title' => 'Quantité empruntée',
                'data' => function ($resource) {
                    return $resource->users->sum(function ($user) {
                        return $user->pivot->quantity ?? 0;
                    });
                },
            ],
            'available_quantity' => [
                'title' => 'Quantité disponible',
                'data' => function ($resource) {
                    $borrowedQuantity = $resource->users->sum(function ($user) {
                        return $user->pivot->quantity ?? 0;
                    });
                    return $resource->quantity - $borrowedQuantity;
                },
            ],
            'borrowers' => [
                'title' => 'Emprunteurs',
                'data' => function ($resource) {
                    $borrowers = $resource->users->map(function ($user) {
                        return $user->name . ' (' . $user->pivot->quantity . ')';
                    });
                    return $borrowers->isNotEmpty()
                        ? $borrowers->implode(', ')
                        : 'Aucun emprunt';
                },
            ],
            'borrowing_frequency' => [
                'title' => 'Fréquence d\'emprunt',
                'data' => function ($resource) {
                    $borrowingCount = $resource->users->count();
                    $totalUsersCount = User::count();

                    $percentage = $totalUsersCount > 0
                        ? round(($borrowingCount / $totalUsersCount) * 100, 2)
                        : 0;

                    return sprintf(
                        '%d emprunt(s) (%s%% des utilisateurs)',
                        $borrowingCount,
                        $percentage
                    );
                },
            ],
            'last_borrowed' => [
                'title' => 'Dernier emprunt',
                'data' => function ($resource) {
                    $lastBorrowed = $resource->users()
                        ->withPivot('borrowed_at')
                        ->latest('borrowed_at')
                        ->first();

                    return $lastBorrowed
                        ? $lastBorrowed->pivot->borrowed_at->format('d/m/Y H:i')
                        : 'Jamais emprunté';
                },
            ],
            'created_at' => [
                'title' => 'Date de création',
                'data' => function ($resource) {
                    return $resource->created_at->format('d/m/Y H:i');
                },
            ],
            'updated_at' => [
                'title' => 'Dernière mise à jour',
                'data' => function ($resource) {
                    return $resource->updated_at->format('d/m/Y H:i');
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
        return "Liste des ressources";
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
        return self::with(['group', 'users'])
            ->withCount('users');
    }
}
