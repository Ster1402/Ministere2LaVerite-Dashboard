<?php

namespace App\Models;

use App\Interfaces\ReportableModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\Reportable;

class Donation extends Model implements ReportableModel
{
    use HasFactory;
    use Reportable;

    protected $fillable = [
        'transaction_id',
        'amount',
        'donor_name',
        'donor_email',
        'donor_phone',
        'donation_date',
        'message',
        'is_anonymous',
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
     * Get the transaction associated with the donation.
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

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
