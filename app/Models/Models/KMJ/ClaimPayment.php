<?php

namespace App\Models\Models\KMJ;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClaimPayment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'claim_payment_number',
        'claim_intimation_number',
        'claim_notification_id',
        'payment_date',
        'paid_amount',
        'payment_mode',
        'parties_notified',
        'net_premium_earned',
        'claim_resulted_litigation',
        'litigation_reason',
        'currency_id',
        'exchange_rate',
        'status',
        'acknowledgement_id',
        'request_id',
        'acknowledgement_status_code',
        'acknowledgement_status_desc',
        'response_id',
        'response_status_code',
        'response_status_desc'
    ];

    public function claimants()
    {
        return $this->hasMany(ClaimPaymentClaimant::class);
    }

    public function claimNotification()
    {
        return $this->belongsTo(ClaimNotification::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
