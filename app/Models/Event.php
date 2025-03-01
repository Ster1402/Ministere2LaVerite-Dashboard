<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;

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
 * @method static \Illuminate\Database\Eloquent\Builder|Event filter(array $filters)
 * @method static \Illuminate\Database\Eloquent\Builder|Event newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Event newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Event query()
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereUserId($value)
 * @mixin \Eloquent
 */
class Event extends Model
{
    use HasFactory;

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'from' => 'datetime',
        'to' => 'datetime',
    ];

    protected $with = ['assemblies'];

    protected $guarded = ['id'];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assemblies(): BelongsToMany
    {
        return $this->belongsToMany(Assembly::class)->withTimestamps();
    }

    // Scopes filter
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
}
