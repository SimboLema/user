<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MuaCallback extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'mua_callbacks';

    protected $fillable = [
        'payload',
    ];

    protected $casts = [
        'payload' => 'array'
    ];
}
