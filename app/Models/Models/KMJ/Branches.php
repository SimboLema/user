<?php

namespace App\Models\Models\KMJ;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branches extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'region',
        'phone',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function region()
{
    return $this->belongsTo(Region::class);
}
}
