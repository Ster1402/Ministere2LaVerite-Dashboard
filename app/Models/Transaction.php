<?php

namespace App\Models;

use App\Interfaces\ReportableModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Traits\Reportable;
use Carbon\Carbon;

class Transaction extends Model implements ReportableModel
{
    use HasFactory;
    use Reportable;

    protected $with = ['user', 'paymentMethod'];

    protected $fillable = [
        'amount',
        'currency',
        'comment',
        'user_id',
        'donor_name',
        'donor_email',
        'donor_phone',
        'payment_method_id',
        'is_processed',
        'transaction_reference'
    ];

    // Existing relationships remain the same
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function donation(): HasOne
    {
        return $this->hasOne(Donation::class);
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
            'amount' => [
                'title' => 'Montant',
                'data' => function ($transaction) {
                    return number_format($transaction->amount, 2, ',', ' ') . ' ' . $transaction->currency;
                },
            ],
            'donor_info' => [
                'title' => 'Donateur',
                'data' => function ($transaction) {
                    // Prioritize user information if available
                    if ($transaction->user) {
                        return $transaction->user->name . ' ' . ($transaction->user->surname ?? '');
                    }

                    // Fallback to donor information from transaction
                    if ($transaction->donor_name) {
                        return $transaction->donor_name;
                    }

                    return 'Donateur anonyme';
                },
            ],
            'contact_details' => [
                'title' => 'Coordonnées',
                'data' => function ($transaction) {
                    // Prioritize user contact info
                    if ($transaction->user) {
                        return implode(' | ', array_filter([
                            $transaction->user->email,
                            $transaction->user->phoneNumber
                        ]));
                    }

                    // Fallback to transaction donor details
                    return implode(' | ', array_filter([
                        $transaction->donor_email,
                        $transaction->donor_phone
                    ])) ?: 'Non renseigné';
                },
            ],
            'payment_method' => [
                'title' => 'Méthode de paiement',
                'data' => function ($transaction) {
                    return $transaction->paymentMethod
                        ? $transaction->paymentMethod->display_name
                        : 'Non spécifié';
                },
            ],
            'transaction_reference' => [
                'title' => 'Référence',
                'data' => function ($transaction) {
                    return $transaction->transaction_reference ?? 'Non disponible';
                },
            ],
            'is_processed' => [
                'title' => 'Statut',
                'data' => function ($transaction) {
                    return $transaction->is_processed
                        ? 'Traité'
                        : 'En attente de traitement';
                }
            ],
            'comment' => [
                'title' => 'Commentaire',
                'data' => function ($transaction) {
                    return $transaction->comment ?? 'Aucun commentaire';
                },
            ],
            'donation_link' => [
                'title' => 'Lien avec un don',
                'data' => function ($transaction) {
                    return $transaction->donation ? 'Oui' : 'Non';
                },
            ],
            'created_at' => [
                'title' => 'Date de transaction',
                'data' => function ($transaction) {
                    return $transaction->created_at->format('d/m/Y H:i');
                },
            ],
            'financial_metrics' => [
                'title' => 'Métriques financières',
                'data' => function ($transaction) {
                    // Potential advanced financial insights
                    $insights = [];

                    // Compare to average transaction amount
                    $avgAmount = self::avg('amount');
                    $comparisonToAverage = $transaction->amount / $avgAmount;

                    $insights[] = sprintf(
                        "Écart à la moyenne : %s (%.2f%%)",
                        $comparisonToAverage > 1 ? 'Supérieur' : 'Inférieur',
                        abs($comparisonToAverage - 1) * 100
                    );

                    return implode(' | ', $insights);
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
        return "Liste des transactions financières";
    }

    /**
     * Get the default ordering for reports.
     *
     * @return string
     */
    public static function getReportDefaultOrder()
    {
        return 'created_at';
    }

    /**
     * Get the report query with eager loaded relationships.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function getReportQuery()
    {
        return self::with(['user', 'paymentMethod', 'donation'])
            ->orderBy('created_at', 'desc');
    }
}
