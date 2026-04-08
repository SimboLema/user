<?php

namespace App\Models\Models\KMJ;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClaimDischargeVoucherClaimant extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'claim_discharge_voucher_claimants';

    protected $fillable = [
        'discharge_voucher_id',
        'claimant_category',
        'claimant_type',
        'claimant_id_number',
        'claimant_id_type',
    ];

    /**
     * Relationships
     */
    public function dischargeVoucher()
    {
        return $this->belongsTo(ClaimDischargeVoucher::class, 'discharge_voucher_id');
    }
}
