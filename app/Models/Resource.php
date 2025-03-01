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
 * @property string $name
 * @property int $quantity
 * @property string|null $description
 * @property int $group_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Group $group
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Database\Factories\ResourceFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Resource filter(array $filters)
 * @method static \Illuminate\Database\Eloquent\Builder|Resource newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Resource newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Resource query()
 * @method static \Illuminate\Database\Eloquent\Builder|Resource whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resource whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resource whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resource whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resource whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resource whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resource whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Resource extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $with = ['group'];

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

    // Scopes filter
    public function scopeFilter(Builder $query, array $filters)
    {
        $query
            ->when($filters["search"] ?? false, static function ($query, $search) {
                $query
                    ->where(static function ($query) use ($search) {
                        $query
                            ->where("name", "like", '%' . $search . '%')
                            ->orWhereHas('group', fn ($q) => $q->where('name', 'like', '%' . $search . '%'));
                    });
            });
    }
}
