<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StrategyCallback extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'strategy_callbacks';

    protected $fillable = [
        'payload',
    ];

    protected $casts = [
        'payload' => 'array'
    ];
}
