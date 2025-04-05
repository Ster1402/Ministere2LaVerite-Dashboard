<?php

namespace App\Models;

use App\Interfaces\FilterableModel;
use App\Interfaces\ReportableModel;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\Reportable;

/**
 * 
 *
 * @property int $id
 * @property string|null $reference
 * @property string $amount
 * @property string|null $donor_name
 * @property string|null $donor_email
 * @property string|null $donor_phone
 * @property \Illuminate\Support\Carbon $donation_date
 * @property string|null $message
 * @property bool $is_anonymous
 * @property int|null $user_id
 * @property int|null $payment_method_id
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\PaymentMethod|null $paymentMethod
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Donation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Donation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Donation query()
 * @method static \Illuminate\Database\Eloquent\Builder|Donation whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Donation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Donation whereDonationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Donation whereDonorEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Donation whereDonorName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Donation whereDonorPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Donation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Donation whereIsAnonymous($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Donation whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Donation wherePaymentMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Donation whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Donation whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Donation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Donation whereUserId($value)
 * @property int|null $transaction_id
 * @property string|null $payment_data
 * @property int $is_pending
 * @property int $is_completed
 * @property int $is_failed
 * @method static \Illuminate\Database\Eloquent\Builder|Donation applyFilters(array $filters)
 * @method static \Illuminate\Database\Eloquent\Builder|Donation whereIsCompleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Donation whereIsFailed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Donation whereIsPending($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Donation wherePaymentData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Donation whereReference($value)
 * @mixin \Eloquent
 */
class Donation extends Model implements ReportableModel, FilterableModel
{
    use HasFactory;
    use Reportable;
    use Filterable;

    protected $fillable = [
        'transaction_id',
        'reference',
        'amount',
        'donor_name',
        'donor_email',
        'donor_phone',
        'donation_date',
        'message',
        'is_anonymous',
        'is_pending',
        'is_completed',
        'is_failed',
        'user_id',
        'payment_method_id',
        'status'
    ];

    protected $casts = [
        'donation_date' => 'datetime',
        'is_anonymous' => 'boolean',
    ];

    protected $with = ['user', 'paymentMethod'];

    /**
     * Get the user who made the donation.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the payment method used for the donation.
     */
    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
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
            'donor_name' => [
                'title' => 'Nom du donateur',
                'data' => function ($donation) {
                    return $donation->is_anonymous ? 'Anonyme' : ($donation->donor_name ?? 'Non renseigné');
                },
            ],
            'donor_email' => [
                'title' => 'Email du donateur',
                'data' => function ($donation) {
                    return $donation->is_anonymous ? 'Anonyme' : ($donation->donor_email ?? 'Non renseigné');
                },
            ],
            'donor_phone' => [
                'title' => 'Téléphone du donateur',
                'data' => function ($donation) {
                    return $donation->is_anonymous ? 'Anonyme' : ($donation->donor_phone ?? 'Non renseigné');
                },
            ],
            'amount' => [
                'title' => 'Montant',
                'data' => function ($donation) {
                    return number_format($donation->amount, 2, ',', ' ') . ' XAF';
                },
            ],
            'donation_date' => [
                'title' => 'Date de don',
                'data' => function ($donation) {
                    return $donation->donation_date ? $donation->donation_date->format('d/m/Y H:i') : 'Non daté';
                },
            ],
            'payment_method' => [
                'title' => 'Méthode de paiement',
                'data' => function ($donation) {
                    return $donation->paymentMethod ? $donation->paymentMethod->display_name : 'Non spécifié';
                },
            ],
            'user' => [
                'title' => 'Utilisateur associé',
                'data' => function ($donation) {
                    return $donation->user ? $donation->user->name . ' ' . $donation->user->surname : 'Aucun';
                },
            ],
            'status' => [
                'title' => 'Statut',
                'data' => function ($donation) {
                    return match ($donation->status) {
                        'pending' => 'En attente',
                        'completed' => 'Complété',
                        'failed' => 'Échoué',
                        default => $donation->status ?? 'Non spécifié'
                    };
                },
            ],
            'is_anonymous' => [
                'title' => 'Don anonyme',
                'data' => function ($donation) {
                    return $donation->is_anonymous ? 'Oui' : 'Non';
                },
            ],
            'reference' => [
                'title' => 'Référence',
                'data' => function ($donation) {
                    return $donation->reference ?? 'Non disponible';
                },
            ],
            'message' => [
                'title' => 'Message',
                'data' => function ($donation) {
                    return $donation->message ?? 'Aucun message';
                },
            ],
            'created_at' => [
                'title' => 'Date de création',
                'data' => function ($donation) {
                    return $donation->created_at->format('d/m/Y H:i');
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
        return "Liste des dons";
    }

    /**
     * Get the default ordering for reports.
     *
     * @return string
     */
    public static function getReportDefaultOrder()
    {
        return 'donation_date';
    }

    /**
     * Get the filterable attributes for this model.
     *
     * @return array
     */
    public static function getFilterableAttributes(): array
    {
        return [
            'id' => [
                'name' => 'id',
                'display_name' => 'ID',
                'type' => 'integer',
                'operators' => ['equals', 'not_equals', 'greater_than', 'less_than'],
            ],
            'reference' => [
                'name' => 'reference',
                'display_name' => 'Référence',
                'type' => 'string',
                'operators' => ['equals', 'not_equals', 'contains', 'is_null', 'is_not_null'],
            ],
            'amount' => [
                'name' => 'amount',
                'display_name' => 'Montant',
                'type' => 'integer', // Using integer type for easier numeric comparison
                'operators' => ['equals', 'not_equals', 'greater_than', 'less_than', 'greater_than_or_equal', 'less_than_or_equal'],
            ],
            'donor_name' => [
                'name' => 'donor_name',
                'display_name' => 'Nom du donateur',
                'type' => 'string',
                'operators' => ['equals', 'not_equals', 'contains', 'starts_with', 'is_null', 'is_not_null'],
            ],
            'donor_email' => [
                'name' => 'donor_email',
                'display_name' => 'Email du donateur',
                'type' => 'string',
                'operators' => ['equals', 'not_equals', 'contains', 'is_null', 'is_not_null'],
            ],
            'donor_phone' => [
                'name' => 'donor_phone',
                'display_name' => 'Téléphone du donateur',
                'type' => 'string',
                'operators' => ['equals', 'not_equals', 'contains', 'is_null', 'is_not_null'],
            ],
            'donation_date' => [
                'name' => 'donation_date',
                'display_name' => 'Date de don',
                'type' => 'date',
                'operators' => ['equals', 'not_equals', 'greater_than', 'less_than'],
            ],
            'message' => [
                'name' => 'message',
                'display_name' => 'Message',
                'type' => 'string',
                'operators' => ['contains', 'is_null', 'is_not_null'],
            ],
            'is_anonymous' => [
                'name' => 'is_anonymous',
                'display_name' => 'Don anonyme',
                'type' => 'boolean',
                'operators' => ['equals', 'not_equals'],
            ],
            'user_id' => [
                'name' => 'user_id',
                'display_name' => 'ID Utilisateur',
                'type' => 'integer',
                'operators' => ['equals', 'not_equals', 'is_null', 'is_not_null'],
            ],
            'payment_method_id' => [
                'name' => 'payment_method_id',
                'display_name' => 'Méthode de paiement ID',
                'type' => 'integer',
                'operators' => ['equals', 'not_equals'],
            ],
            'payment_method_name' => [
                'name' => 'payment_method_name',
                'display_name' => 'Méthode de paiement',
                'type' => 'string',
                'operators' => ['equals', 'not_equals', 'contains'],
                'custom_query' => true,
            ],
            'status' => [
                'name' => 'status',
                'display_name' => 'Statut',
                'type' => 'string',
                'operators' => ['equals', 'not_equals'],
            ],
            'created_at' => [
                'name' => 'created_at',
                'display_name' => 'Date de création',
                'type' => 'datetime',
                'operators' => ['greater_than', 'less_than'],
            ],
            'updated_at' => [
                'name' => 'updated_at',
                'display_name' => 'Date de mise à jour',
                'type' => 'datetime',
                'operators' => ['greater_than', 'less_than'],
            ],
        ];
    }

    /**
     * Apply dynamic filters specific to this model.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function applyDynamicFilters($query, array $filters)
    {
        foreach ($filters as $filter) {
            if (
                !isset($filter['field'], $filter['operator'], $filter['value']) &&
                !in_array($filter['operator'], ['is_null', 'is_not_null'])
            ) {
                continue;
            }

            // Handle payment_method_name custom filter
            if ($filter['field'] === 'payment_method_name') {
                $query = self::applyPaymentMethodNameFilter($query, $filter['operator'], $filter['value']);
                continue;
            }
        }

        // Apply standard filters from the Filterable trait
        return parent::applyDynamicFilters($query, $filters);
    }

    /**
     * Apply filter for payment method name.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $operator
     * @param string $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private static function applyPaymentMethodNameFilter($query, string $operator, string $value)
    {
        switch ($operator) {
            case 'equals':
                return $query->whereHas('paymentMethod', function ($q) use ($value) {
                    $q->where('name', $value)
                        ->orWhere('display_name', $value);
                });
            case 'not_equals':
                return $query->whereDoesntHave('paymentMethod', function ($q) use ($value) {
                    $q->where('name', $value)
                        ->orWhere('display_name', $value);
                });
            case 'contains':
                return $query->whereHas('paymentMethod', function ($q) use ($value) {
                    $q->where('name', 'like', '%' . $value . '%')
                        ->orWhere('display_name', 'like', '%' . $value . '%');
                });
            default:
                return $query;
        }
    }
}
