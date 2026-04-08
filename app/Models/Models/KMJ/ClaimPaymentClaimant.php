<?php

namespace App\Models\Models\KMJ;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaimPaymentClaimant extends Model
{
    use HasFactory;

    protected $fillable = [
        'claim_payment_id',
        'claimant_category',
        'claimant_type',
        'claimant_id_number',
        'claimant_id_type'
    ];

    public function payment()
    {
        return $this->belongsTo(ClaimPayment::class, 'claim_payment_id');
    }
}
