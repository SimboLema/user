<?php

namespace App\Models\Models\KMJ;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reinsurance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'quotation_id',
        'currency_id',
        'reinsurance_category_id',
        'exchange_rate',
        'authorizing_officer_name',
        'authorizing_officer_title',
        'status',
        'acknowledgement_id',
        'request_id',
        'acknowledgement_status_code',
        'acknowledgement_status_desc',
        'response_id',
        'response_status_code',
        'response_status_desc',
    ];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function category()
    {
        return $this->belongsTo(ReinsuranceCategory::class, 'reinsurance_category_id');
    }

    public function participants()
    {
        return $this->hasMany(ReinsuranceParticipant::class);
    }
}
