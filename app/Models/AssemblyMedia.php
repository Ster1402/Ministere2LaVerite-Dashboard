<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $assembly_id
 * @property int $media_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\AssemblyMediaFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|AssemblyMedia newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AssemblyMedia newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AssemblyMedia query()
 * @method static \Illuminate\Database\Eloquent\Builder|AssemblyMedia whereAssemblyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssemblyMedia whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssemblyMedia whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssemblyMedia whereMediaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssemblyMedia whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AssemblyMedia extends Model
{
    use HasFactory;
}
