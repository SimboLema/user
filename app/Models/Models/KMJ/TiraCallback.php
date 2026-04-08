<?php

namespace App\Models\Models\KMJ;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TiraCallback extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'raw_data',
    ];
}
