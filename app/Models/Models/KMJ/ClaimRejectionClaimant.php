<?php

namespace App\Models\Models\KMJ;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClaimRejectionClaimant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'claim_rejection_id',
        'claimant_category',
        'claimant_type',
        'claimant_id_number',
        'claimant_id_type',
    ];

    public function rejection()
    {
        return $this->belongsTo(ClaimRejection::class, 'claim_rejection_id');
    }
}
