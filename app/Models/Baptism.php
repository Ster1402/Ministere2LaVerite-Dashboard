<?php

namespace App\Models;

use App\DTOs\Item;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $type Values: none, water, holy-spirit, both-water-and-holy-spirit
 * @property string|null $nominalMaker
 * @property int $hasHolySpirit
 * @property string|null $ministerialLevel
 * @property int $spiritualLevel
 * @property string|null $on
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\BaptismFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Baptism filter(array $filters)
 * @method static \Illuminate\Database\Eloquent\Builder|Baptism newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Baptism newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Baptism query()
 * @method static \Illuminate\Database\Eloquent\Builder|Baptism whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Baptism whereHasHolySpirit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Baptism whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Baptism whereMinisterialLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Baptism whereNominalMaker($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Baptism whereOn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Baptism whereSpiritualLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Baptism whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Baptism whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Baptism whereUserId($value)
 * @property \Illuminate\Support\Carbon|null $date_water
 * @property \Illuminate\Support\Carbon|null $date_holy_spirit
 * @property \Illuminate\Support\Carbon|null $date_latest
 * @method static Builder|Baptism whereDateHolySpirit($value)
 * @method static Builder|Baptism whereDateLatest($value)
 * @method static Builder|Baptism whereDateWater($value)
 * @mixin \Eloquent
 */
class Baptism extends Model
{
    use HasFactory;

    public static function sacerdotalLevels(): Collection {
        return collect([
            new Item('none', 'Aucun'),
            new Item('worker', 'Ouvrier'),
            new Item('elder', 'Ancien(ne)'),
            new Item('consecrated', 'Communiant'),
            new Item('responsible', 'Responsable'),
            new Item('predictor', 'Prédicateur'),
            new Item('apostle', 'Apôtre'),
            new Item('evangelist', 'Évangéliste'),
            new Item('pastor', 'Pasteur'),
//            new Item('priest', 'Prêtre'),
            new Item('deacon', 'Diacre/Diaconnaise'),
//            new Item('bishop', 'Évêque'),
//            new Item('archbishop', 'Archevêque'),
//            new Item('pope', 'Pape'),
        ]);
    }

    protected $guarded = ['id'];

    protected $casts = [
        'date_water' => 'datetime',
        'date_holy_spirit' => 'datetime',
        'date_latest' => 'datetime',
        'hasHolySpirit' => 'boolean',
        'spiritualLevel' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes filter
    public function scopeFilter(Builder $query, array $filters)
    {
        $query
            ->when($filters["search"] ?? false, static function ($query, $search) {
                $query
                    ->where(static function ($query) use ($search) {
                        $query
                            ->where("type", "like", '%' . $search . '%')
                            ->orWhere("nominalMaker", "like", '%' . $search . '%')
                            ->orWhere("ministerialLevel", "like", '%' . $search . '%');
                    });
            });
    }
}
