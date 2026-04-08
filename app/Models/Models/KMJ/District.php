<?php

namespace App\Models\Models\KMJ;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class District extends Model
{
    use HasFactory, SoftDeletes;

     protected $fillable = ['name', 'code', 'region_id'];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

}
