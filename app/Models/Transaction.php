<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transaction extends Model
{
    use HasFactory;

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    /**
     * Get the donation associated with the transaction.
     */
    public function donation(): HasOne
    {
        return $this->hasOne(Donation::class);
    }
}
