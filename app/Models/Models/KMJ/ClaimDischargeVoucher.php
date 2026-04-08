<?php

namespace App\Models\Models\KMJ;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClaimDischargeVoucher extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'claim_discharge_vouchers';

    protected $fillable = [
        'claim_notification_id',
        'discharge_voucher_number',
        'claim_assessment_number',
        // 'cover_note_reference_number',
        'discharge_voucher_date',
        'currency_id',
        'exchange_rate',
        'claim_offer_communication_date',
        'claim_offer_amount',
        'claimant_response_date',
        'adjustment_date',
        'adjustment_reason',
        'adjustment_amount',
        'reconciliation_date',
        'reconciliation_summary',
        'reconciled_amount',
        'offer_accepted',
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
    public function claimNotification()
    {
        return $this->belongsTo(ClaimNotification::class, 'claim_notification_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function claimants()
    {
        return $this->hasMany(ClaimDischargeVoucherClaimant::class, 'discharge_voucher_id');
    }
}
