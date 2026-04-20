<?php

namespace App\Models\Models\KMJ;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Models\KMJ\Branches;

class Region extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'code', 'country_id'];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function districts()
    {
        return $this->hasMany(District::class);
    }

    public function branches()
{
    return $this->hasMany(Branches::class);
}
}
