<?php

namespace App\Models\Models\KMJ;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AddonProduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'addon_products';

    protected $fillable = [
        'name',
        'description',
        'amount',
        'amount_type',
        'rate',
        'applicable_to',
    ];

    protected $casts = [
        'applicable_to' => 'array',
    ];
}
