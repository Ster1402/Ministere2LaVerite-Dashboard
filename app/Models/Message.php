<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * 
 *
 * @property int $id
 * @property string|null $subject
 * @property string|null $content
 * @property int|null $senderId
 * @property int|null $receiverId
 * @property int|null $assembly_id
 * @property string|null $category
 * @property string|null $picture_path
 * @property string|null $tags
 * @property int $received
 * @property int $seen
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Assembly|null $assembly
 * @property-read \App\Models\User|null $receiver
 * @property-read \App\Models\User|null $sender
 * @method static \Database\Factories\MessageFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Message filter(array $filters)
 * @method static \Illuminate\Database\Eloquent\Builder|Message newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Message newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Message query()
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereAssemblyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message wherePicturePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereReceived($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereReceiverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereSeen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereSenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereTags($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereUpdatedAt($value)
 * @property int|null $message_id The id of the message we're replying to.
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Assembly> $assemblies
 * @property-read int|null $assemblies_count
 * @property-read Message|null $message
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Message> $replies
 * @property-read int|null $replies_count
 * @method static Builder|Message whereMessageId($value)
 * @property int $deleted
 * @method static Builder|Message whereDeleted($value)
 * @mixin \Eloquent
 */
class Message extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'senderId');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiverId');
    }

    public function message(): BelongsTo
    {
        return $this->belongsTo(self::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(self::class);
    }

    public function assemblies(): BelongsToMany
    {
        return $this->belongsToMany(Assembly::class, 'assembly_messages');
    }

    // Scopes filter
    public function scopeFilter(Builder $query, array $filters)
    {
        $query
            ->when($filters["search"] ?? false, static function ($query, $search) {
                $query
                    ->where(static function ($query) use ($search) {
                        $query
                            ->where("subject", "like", '%' . $search . '%')
                            ->orWhere('content', "like", '%' . $search . '%')
                            ->orWhereHas('sender', fn ($q) => $q->where('name', 'like', '%' . $search . '%'))
                            ->orWhereHas('sender', fn ($q) => $q->where('email', 'like', '%' . $search . '%'));
                    });
            });

        $query
            ->when($filters["category"] ?? false, static function ($query, $search) {
                $query
                    ->where(static function ($query) use ($search) {
                        $query
                            ->where("category", "=", $search);
                    });
            });

        $query
            ->when($filters["assembly"] ?? false, static function ($query, $search) {
                $query
                    ->where(static function ($query) use ($search) {
                        $query
                            ->whereHas('assemblies', fn ($q) => $q->where('name', 'like', '%' . $search . '%'));
                    });
            });

        $query
            ->when($filters["author"] ?? false, static function ($query, $search) {
                $query
                    ->where(static function ($query) use ($search) {
                        $query
                            ->whereHas('sender', fn ($q) => $q->where('id', $search));
                    });
            });
    }
}
