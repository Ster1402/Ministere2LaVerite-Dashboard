<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $displayName
 * @property string $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Database\Factories\RolesFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Roles filter(array $filters)
 * @method static \Illuminate\Database\Eloquent\Builder|Roles newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Roles newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Roles query()
 * @method static \Illuminate\Database\Eloquent\Builder|Roles whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Roles whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Roles whereDisplayName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Roles whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Roles whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Roles whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Roles extends Model
{
    use HasFactory;

    // Apps roles known
    public static string $SUDO = 'sudo';
    public static string $ADMIN = 'admin';
    public static string $USER_MANAGER = 'user_manager';
    public static string $RESOURCE_MANAGER = 'resource_manager';
    public static string $ASSEMBLY_MANAGER = 'assembly_manager';
    public static string $EVENT_MANAGER = 'event_manager';
    public static string $MEDIA_MANAGER = 'media_manager';
    public static string $HUB_MANAGER = 'hub_manager';
    public static string $END_USER = 'end_user';

    // Sector management
    public static string $SECTOR_MANAGER = 'sector_manager';
    public static string $SUBSECTOR_ADMIN = 'subsector_manager';

    // Available roles
    public static function availableAdminsRoles(): Collection {
        return collect([
            Roles::$SUDO,
            Roles::$ADMIN,
            Roles::$ASSEMBLY_MANAGER,
            Roles::$EVENT_MANAGER,
            Roles::$RESOURCE_MANAGER,
            Roles::$MEDIA_MANAGER,
            Roles::$USER_MANAGER,
            Roles::$HUB_MANAGER,
            Roles::$SECTOR_MANAGER,
            Roles::$SUBSECTOR_ADMIN,
        ]);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'role_user',
            'role_id',
            'user_id'
        );
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
                            ->orWhere("displayName", "like", '%' . $search . '%');
                    });
            });
    }
}
