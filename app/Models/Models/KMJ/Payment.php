<?php

namespace App\Models\Models\KMJ;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'quotation_id',
        'quotation_endorsement_id',
        'payment_mode_id',
        'amount',
        'payment_date',
        'cheque_number',
        'cheque_bank_name',
        'cheque_date',
        'reference_no',
        'eft_payment_phone_no',
        'status',
        'created_by',
    ];

    // Relationships

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function paymentMode()
    {
        return $this->belongsTo(PaymentMode::class);
    }
    public function quotationEndorsement()
    {
        return $this->belongsTo(QuotationEndorsement::class);
    }
}
