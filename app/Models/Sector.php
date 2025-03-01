<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int|null $master_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Sector|null $master
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Sector> $subsectors
 * @property-read int|null $subsectors_count
 * @method static \Database\Factories\SectorFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Sector filter(array $filters)
 * @method static \Illuminate\Database\Eloquent\Builder|Sector newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sector newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sector query()
 * @method static \Illuminate\Database\Eloquent\Builder|Sector whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sector whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sector whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sector whereMasterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sector whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sector whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Sector extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $with = ['master'];

    public function master(): BelongsTo
    {
        return $this->belongsTo(Sector::class, 'master_id');
    }

    public function subsectors(): HasMany
    {
        return $this->hasMany(Sector::class);
    }

    // Scopes filter
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
}
