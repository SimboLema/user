<?php

namespace App\Models\Models\KMJ;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PolicySubmission extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'policy_number',
        'policy_operative_clause',
        'special_conditions',
        'exclusions',
        'cover_note_reference_numbers',
        'status',
        'acknowledgement_id',
        'request_id',
        'acknowledgement_status_code',
        'acknowledgement_status_desc',
        'response_id',
        'response_status_code',
        'response_status_desc',
    ];

    protected $casts = [
        'cover_note_reference_numbers' => 'array',
    ];
}
