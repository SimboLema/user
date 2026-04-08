<?php

namespace App\Models\Models\KMJ;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClaimNotification extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'quotation_id',
        'claim_notification_number',
        'claim_report_date',
        'claim_form_dully_filled',
        'loss_date',
        'loss_nature',
        'loss_type',
        'loss_location',
        'officer_name',
        'officer_title',
        'claim_notification_status',
        'status',
        'acknowledgement_id',
        'request_id',
        'acknowledgement_status_code',
        'acknowledgement_status_desc',
        'response_id',
        'claim_reference_number',
        'response_status_code',
        'response_status_desc',
    ];

    // Relationship to quotation
    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }
}
