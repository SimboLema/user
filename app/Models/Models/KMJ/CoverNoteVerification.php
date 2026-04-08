<?php

namespace App\Models\Models\KMJ;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoverNoteVerification extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'cover_note_reference_number',
        'sticker_number',
        'motor_registration_number',
        'motor_chassis_number',
        'status',
        'response_id',
        'request_id',
        'response_status_code',
        'response_status_desc',
        'tira_response',
    ];

    protected $casts = [
        'tira_response' => 'array',
    ];
}
