<?php

namespace App\Models\Models\KMJ;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'dob',
        'policy_holder_type_id',
        'policy_holder_id_number',
        'tin_number',
        'policy_holder_id_type_id',
        'gender',
        'district_id',
        'street',
        'phone',
        'fax',
        'postal_address',
        'email_address',
    ];

    protected $casts = [
        'dob' => 'date',
    ];

    public function quotations()
    {
        return $this->hasMany(Quotation::class, 'customer_id');
    }

    public function policyHolderType()
    {
        return $this->belongsTo(PolicyHolderType::class, 'policy_holder_type_id');
    }

    public function policyHolderIdType()
    {
        return $this->belongsTo(PolicyHolderIdType::class, 'policy_holder_id_type_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }
}
