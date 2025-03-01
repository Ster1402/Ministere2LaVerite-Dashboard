<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message> $messages
 * @property-read int|null $messages_count
 * @property-read \App\Models\Sector $sector
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Database\Factories\AssemblyFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Assembly filter(array $filters)
 * @method static \Illuminate\Database\Eloquent\Builder|Assembly newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Assembly newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Assembly query()
 * @method static \Illuminate\Database\Eloquent\Builder|Assembly whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Assembly whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Assembly whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Assembly whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Assembly whereSectorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Assembly whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Media> $medias
 * @property-read int|null $medias_count
 * @mixin \Eloquent
 */
class Assembly extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $with = ['sector'];

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

    // Scopes filter
    public function scopeFilter(Builder $query, array $filters)
    {
        $query
            ->when($filters["search"] ?? false, static function ($query, $search) {
                $query
                    ->where(static function ($query) use ($search) {
                        $query
                            ->where("name", "like", '%' . $search . '%')
                            ->orWhereHas('sector', fn ($q) => $q->where('name', 'like', '%' . $search . '%'));
                    });
            });
    }
}
