<?php

namespace App\Models\Models\KMJ;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Addon extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'addons';

    protected $fillable = [
        'quotation_id',
        'quotation_fleet_details_id',
        'addon_product_id',
        'addon_reference',
        'addon_desc',
        'addon_amount',
        'addon_rate',
        'premium_excluding_tax',
        'premium_excluding_tax_equivalent',
        'premium_including_tax',
        'tax_code',
        'is_tax_exempted',
        'tax_exemption_type',
        'tax_exemption_reference',
        'tax_rate',
        'tax_amount',
    ];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'quotation_id');
    }

    public function quotationFleetDetail()
    {
        return $this->belongsTo(QuotationFleetDetail::class, 'quotation_fleet_details_id');
    }


    public function addonProduct()
    {
        return $this->belongsTo(AddonProduct::class, 'addon_product_id');
    }
}
