<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Donation extends Model
{
    use HasFactory;

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
}
