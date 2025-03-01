<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property int $resource_id
 * @property int $quantity
 * @property string|null $borrowed_at
 * @property string|null $returned_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\BorrowedFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Borrowed newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Borrowed newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Borrowed query()
 * @method static \Illuminate\Database\Eloquent\Builder|Borrowed whereBorrowedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Borrowed whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Borrowed whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Borrowed whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Borrowed whereResourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Borrowed whereReturnedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Borrowed whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Borrowed whereUserId($value)
 * @mixin \Eloquent
 */
class Borrowed extends Pivot
{
    use HasFactory;
}
