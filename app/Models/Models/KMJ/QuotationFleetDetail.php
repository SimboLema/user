<?php

namespace App\Models\Models\KMJ;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuotationFleetDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'quotation_fleet_details';

    protected $fillable = [
        // Fleet / CoverNote fields
        'fleet_entry',
        'cover_note_number',
        'cover_note_reference_number',
        'prev_cover_note_reference_number',
        'cover_note_desc',
        'operative_clause',
        'endorsement_type',
        'endorsement_reason',
        'endorsement_premium_earned',

        // RiskCovered fields
        'risk_code',
        'sum_insured',
        'sum_insured_equivalent',
        'premium_rate',
        'premium_before_discount',
        'premium_after_discount',
        'premium_excluding_tax_equivalent',
        'premium_including_tax',
        'discount_type',
        'discount_rate',
        'discount_amount',
        'tax_code',
        'is_tax_exempted',
        'tax_exemption_type',
        'tax_exemption_reference',
        'tax_rate',
        'tax_amount',

        // SubjectMatter
        'subject_matter_reference',
        'subject_matter_desc',

        // MotorDtl fields
        'motor_category',
        'motor_type',
        'registration_number',
        'chassis_number',
        'make',
        'model',
        'model_number',
        'body_type',
        'color',
        'engine_number',
        'engine_capacity',
        'fuel_used',
        'number_of_axles',
        'axle_distance',
        'sitting_capacity',
        'year_of_manufacture',
        'tare_weight',
        'gross_weight',
        'motor_usage',
        'owner_name',
        'owner_category',
        'owner_address',

        // Relations
        'quotation_id',
        'addons',

        // -------------------------
        // TIRA response
        // -------------------------
        'acknowledgement_id',
        'request_id',
        'acknowledgement_status_code',
        'acknowledgement_status_desc',

        // -------------------------
        // Form TIRA callback
        // -------------------------
        'response_id',
        'sticker_number',
        'response_status_code',
        'response_status_desc',

        'status',


    ];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function quotationAddons()
    {
        return $this->hasMany(Addon::class, 'quotation_fleet_details_id');
    }
}
