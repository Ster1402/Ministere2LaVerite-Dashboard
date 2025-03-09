<?php

namespace App\Models;

use App\Interfaces\ReportableModel;
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
 * @mixin \Eloquent
 */
class Donation extends Model implements ReportableModel
{
    use HasFactory;
    use Reportable;

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
                    return $donation->paymentMethod ? $donation->paymentMethod->name : 'Non spécifié';
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
}
