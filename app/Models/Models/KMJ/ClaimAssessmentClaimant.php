<?php

namespace App\Models\Models\KMJ;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClaimAssessmentClaimant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'claim_assessment_id',
        'claimant_category',
        'claimant_type',
        'claimant_id_number',
        'claimant_id_type'
    ];

    public function assessment()
    {
        return $this->belongsTo(ClaimAssessment::class, 'claim_assessment_id');
    }
}
