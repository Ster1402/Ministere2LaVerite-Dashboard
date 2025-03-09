<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Interfaces\ReportableModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use App\Traits\Reportable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string|null $surname
 * @property string $gender
 * @property string $email
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property string|null $two_factor_confirmed_at
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property \Illuminate\Support\Carbon|null $dateOfBirth
 * @property string|null $residence
 * @property string|null $phoneNumber
 * @property string|null $antecedent
 * @property string|null $profile_photo_path
 * @property int|null $current_team_id
 * @property int $isActive
 * @property \Illuminate\Support\Carbon|null $arrivalDate
 * @property string|null $maritalStatus
 * @property int $numberOfChildren
 * @property int $sterileWoman
 * @property string|null $seriousIllnesses
 * @property string|null $comment
 * @property int|null $assembly_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $remember_token
 * @property-read \App\Models\Assembly|null $assembly
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Baptism> $baptisms
 * @property-read int|null $baptisms_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Media> $documents
 * @property-read int|null $documents_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Event> $events
 * @property-read int|null $events_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message> $messagesReceived
 * @property-read int|null $messages_received_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message> $messagesSent
 * @property-read int|null $messages_sent_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read string $profile_photo_url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Resource> $resources
 * @property-read int|null $resources_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Roles> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transaction> $transactions
 * @property-read int|null $transactions_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User filter(array $filters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAntecedent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereArrivalDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAssemblyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCurrentTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDateOfBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMaritalStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereNumberOfChildren($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProfilePhotoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereResidence($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSeriousIllnesses($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSterileWoman($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSurname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorConfirmedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorRecoveryCodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @property string|null $profession
 * @property int $isDisciplined
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Media> $medias
 * @property-read int|null $medias_count
 * @method static Builder|User whereIsDisciplined($value)
 * @method static Builder|User whereProfession($value)
 * @property string|null $profession_details
 * @method static Builder|User whereProfessionDetails($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable implements ReportableModel
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use Reportable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    protected $with = ['roles', 'baptisms'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'dateOfBirth' => 'datetime',
        'arrivalDate' => 'datetime',
        'isActive' => 'boolean',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function getProfilePhotoUrlAttribute()
    {
        if (!$this->attributes['profile_photo_path']) {
            return 'https://ui-avatars.com/api/?name=' . $this->name . '+' . $this->surname . '&color=7F9CF5&background=EBF4FF';
        }

        $baseUrl = config('app.client_url');

        return $baseUrl . '/storage/' . $this->attributes['profile_photo_path'];
    }

    /* Relationships --------------------------------*/
    // In User model
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Roles::class, 'role_user', 'user_id', 'role_id')
            ->withPivot('created_at', 'updated_at') // Explicitly include pivot timestamps
            ->withTimestamps(); // Ensure Laravel manages timestamps
    }

    public function messagesSent(): HasMany
    {
        return $this->hasMany(Message::class, 'senderId');
    }

    public function messagesReceived(): HasMany
    {
        return $this->hasMany(Message::class, 'receiverId');
    }

    public function assembly(): BelongsTo
    {
        return $this->belongsTo(Assembly::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function medias(): HasMany
    {
        return $this->hasMany(Media::class);
    }

    public function resources(): BelongsToMany
    {
        return $this->belongsToMany(Resource::class, 'borrowed')
            ->withPivot('borrowed_at', 'returned_at', 'quantity')
            ->withTimestamps();
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function baptisms(): HasMany
    {
        return $this->hasMany(Baptism::class);
    }

    // Utilities functions --------------------------------
    public function isSuperAdmin(): bool
    {
        return $this->roles->contains('name', Roles::$SUDO);
    }

    public function isAdmin(): bool
    {
        return $this->roles->contains('name', Roles::$ADMIN)
            || $this->isSuperAdmin();
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
                            ->orWhere("surname", "like", '%' . $search . '%')
                            ->orWhere("email", "like", '%' . $search . '%');
                    });
            });
    }

    /**
     * Get reportable columns for this model.
     *
     * @return array
     */
    public static function getReportableColumns()
    {
        return [
            'id' => [
                'title' => 'ID',
                'data' => 'id',
            ],
            'name' => [
                'title' => 'Nom',
                'data' => 'name',
            ],
            'surname' => [
                'title' => 'Prénom',
                'data' => 'surname',
            ],
            'email' => [
                'title' => 'Email',
                'data' => 'email',
            ],
            'gender' => [
                'title' => 'Genre',
                'data' => function ($user) {
                    return $user->gender === 'male' ? 'Homme' : ($user->gender === 'female' ? 'Femme' : 'Non spécifié');
                },
            ],
            'phone_number' => [
                'title' => 'Téléphone',
                'data' => 'phoneNumber',
            ],
            'residence' => [
                'title' => 'Adresse',
                'data' => 'residence',
            ],
            'date_of_birth' => [
                'title' => 'Date de naissance',
                'data' => function ($user) {
                    return $user->dateOfBirth ? $user->dateOfBirth->format('d/m/Y') : '';
                },
            ],
            'arrival_date' => [
                'title' => "Date d'arrivée",
                'data' => function ($user) {
                    return $user->arrivalDate ? $user->arrivalDate->format('d/m/Y') : '';
                },
            ],
            'marital_status' => [
                'title' => 'Statut marital',
                'data' => 'maritalStatus',
            ],
            'number_of_children' => [
                'title' => "Nombre d'enfants",
                'data' => 'numberOfChildren',
            ],
            'roles' => [
                'title' => 'Rôles',
                'data' => function ($user) {
                    return $user->roles->pluck('displayName')->implode(', ');
                },
            ],
            'assembly' => [
                'title' => 'Assemblée',
                'data' => function ($user) {
                    return $user->assembly ? $user->assembly->name : 'Non assigné';
                },
            ],
            'is_active' => [
                'title' => 'Statut',
                'data' => function ($user) {
                    return $user->isActive ? 'Actif' : 'Inactif';
                },
            ],
            'created_at' => [
                'title' => 'Date de création',
                'data' => function ($user) {
                    return $user->created_at->format('d/m/Y H:i');
                },
            ],
        ];
    }

    /**
     * Get the report title.
     *
     * @return string
     */
    public static function getReportTitle()
    {
        return "Liste des utilisateurs";
    }

    /**
     * Get the default ordering for reports.
     *
     * @return string
     */
    public static function getReportDefaultOrder()
    {
        return 'name';
    }
}
