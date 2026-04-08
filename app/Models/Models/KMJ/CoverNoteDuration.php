<?php

namespace App\Models\Models\KMJ;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoverNoteDuration extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cover_note_durations';

    protected $fillable = [
        'months',
        'label',
    ];
}
