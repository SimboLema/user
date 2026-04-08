<?php

namespace App\Models\Models\KMJ;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClaimIntimationClaimant extends Model
{

    use HasFactory, SoftDeletes;

    protected $fillable = [
        'claim_intimation_id',
        'claimant_name',
        'claimant_birth_date',
        'claimant_category',
        'claimant_type',
        'claimant_id_number',
        'claimant_id_type',
        'gender',
        'district_id',
        'street',
        'claimant_phone_number',
        'claimant_fax',
        'postal_address',
        'email_address',
    ];

    /**
     * Relationships
     */

    // Each claimant belongs to one claim intimation
    public function claimIntimation()
    {
        return $this->belongsTo(ClaimIntimation::class);
    }

    // Optional relationship if you have a District model
    public function district()
    {
        return $this->belongsTo(District::class);
    }
}
