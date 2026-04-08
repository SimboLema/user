<?php

namespace App\Models\Models\KMJ;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClaimRejection extends Model
{
       use HasFactory, SoftDeletes;

    protected $fillable = [
        'claim_notification_id',
        'claim_rejection_number',
        'claim_intimation_number',
        'rejection_date',
        'rejection_reason',
        'claim_resulted_litigation',
        'claim_amount',
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
        return $this->hasMany(ClaimRejectionClaimant::class);
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
