<?php

namespace App\Models\Models\KMJ;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClaimAssessment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'claim_notification_id',
        'claim_assessment_number',
        'claim_intimation_number',
        'assessment_received_date',
        'assessment_report_summary',
        'currency_id',
        'exchange_rate',
        'assessment_amount',
        'approved_claim_amount',
        'claim_approval_date',
        'claim_approval_authority',
        'is_re_assessment',
        'status',
        'acknowledgement_id',
        'request_id',
        'acknowledgement_status_code',
        'acknowledgement_status_desc',
        'response_id',
        'response_status_code',
        'response_status_desc'
    ];

    public function claimNotification()
    {
        return $this->belongsTo(ClaimNotification::class);
    }

    public function claimants()
    {
        return $this->hasMany(ClaimAssessmentClaimant::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
