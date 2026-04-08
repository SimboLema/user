<?php

namespace App\Models\Models\KMJ;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClaimIntimation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'claim_notification_id',
        'claim_intimation_number',
        'claim_intimation_date',
        'currency_id',
        'exchange_rate',
        'claim_estimated_amount',
        'claim_reserve_amount',
        'claim_reserve_method',
        'loss_assessment_option',
        'assessor_name',
        'assessor_id_number',
        'assessor_id_type',
        'status',
        'acknowledgement_id',
        'request_id',
        'acknowledgement_status_code',
        'acknowledgement_status_desc',
        'response_id',
        'response_status_code',
        'response_status_desc',
    ];

    /**
     * Relationships
     */

    // Each claim intimation belongs to one claim notification
    public function claimNotification()
    {
        return $this->belongsTo(ClaimNotification::class);
    }

    // Each claim intimation can have many claimants
    public function claimants()
    {
        return $this->hasMany(ClaimIntimationClaimant::class);
    }

    // Optional relationship with currency
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
