<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SanlamCallback extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "sanlam_callback";

    protected $fillable = [
        'payload'
    ];

    protected $casts = [
        'payload' => 'array'
    ];
}
