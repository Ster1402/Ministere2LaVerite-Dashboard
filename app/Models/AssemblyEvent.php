<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * 
 *
 * @property int $id
 * @property int $assembly_id
 * @property int $event_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Assembly $assembly
 * @property-read \App\Models\Event $event
 * @method static \Database\Factories\AssemblyEventFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|AssemblyEvent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AssemblyEvent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AssemblyEvent query()
 * @method static \Illuminate\Database\Eloquent\Builder|AssemblyEvent whereAssemblyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssemblyEvent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssemblyEvent whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssemblyEvent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssemblyEvent whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AssemblyEvent extends Pivot
{
    use HasFactory;

    public function assembly(): BelongsTo
    {
        return $this->belongsTo(Assembly::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
