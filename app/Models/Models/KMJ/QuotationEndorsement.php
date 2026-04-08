<?php

namespace App\Models\Models\KMJ;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuotationEndorsement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'quotation_id',
        'quotation_fleet_detail_id',
        'endorsement_type_id',
        'previous_covernote_reference_number',
        'description',
        'old_premium',
        'new_premium',
        'endorsement_premium_earned',
        'cancellation_date',
        'status',

        // -------------------------
        // TIRA response
        // -------------------------
        'acknowledgement_id',
        'request_id',
        'acknowledgement_status_code',
        'acknowledgement_status_desc',

        // -------------------------
        // Form TIRA callback
        // -------------------------
        'response_id',
        'sticker_number',
        'response_status_code',
        'response_status_desc',
    ];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function quotationFleetDetail()
    {
        return $this->belongsTo(QuotationFleetDetail::class, 'quotation_fleet_detail_id');
    }

    public function endorsementType()
    {
        return $this->belongsTo(EndorsementType::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'quotation_endorsement_id');
    }
}
