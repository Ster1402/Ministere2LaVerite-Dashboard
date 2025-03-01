<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $assembly_id
 * @property int $message_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\AssemblyMessageFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|AssemblyMessage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AssemblyMessage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AssemblyMessage query()
 * @method static \Illuminate\Database\Eloquent\Builder|AssemblyMessage whereAssemblyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssemblyMessage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssemblyMessage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssemblyMessage whereMessageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssemblyMessage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AssemblyMessage extends Model
{
    use HasFactory;
}
