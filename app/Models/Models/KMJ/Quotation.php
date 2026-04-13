<?php

namespace App\Models\Models\KMJ;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quotation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        // Cover note and policy
        // -------------------------
        'insuarer_id',
        'fleet_id',
        'fleet_type',
        'fleet_size',
        'comprehensive_insured',
        'addons',

        'coverage_id',
        'customer_id',
        'cover_note_type_id',
        'cover_note_duration_id',
        'payment_mode_id',
        'currency_id',
        'sale_point_code',
        'cover_note_desc',
        'operative_clause',
        'cover_note_start_date',
        'prev_cover_note_reference_number',
        'cover_note_end_date',
        'exchange_rate',
        'total_premium_excluding_tax',
        'total_premium_including_tax',
        'commission_paid',
        'commission_rate',
        'cover_note_reference',
        'sum_insured',
        'sum_insured_equivalent',
        'premium_rate',
        'premium_before_discount',
        'premium_after_discount',
        'premium_excluding_tax_equivalent',
        'premium_including_tax',
        'tax_code',
        'is_tax_exempted',
        'tax_rate',
        'tax_amount',

        // -------------------------
        // Motor data
        // -------------------------
        'endorsement_type_id',
        'endorsement_reason',
        'endorsement_premium_earned',

        // -------------------------
        // Motor data
        // -------------------------
        'motor_category_id',
        'motor_type_id',
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
        'motor_usage_id',
        'owner_name',
        'owner_category_id',
        'owner_address',

        // -------------------------
        // TIRA response
        // -------------------------
        'acknowledgement_id',
        'request_id',
        'acknowledgement_status_code',
        'acknowledgement_status_desc',

        // -------------------------
        // Subject matter
        // -------------------------
        'subject_matter_reference',
        'subject_matter_description',

        // -------------------------
        // Form TIRA callback
        // -------------------------
        'response_id',
        'sticker_number',
        'response_status_code',
        'response_status_desc',

        // -------------------------
        // Auth user
        // -------------------------
        'created_by',
        'updated_by',

        'status',
        'approved_by',
        'approved_at',
        'rejected_by',
        'rejected_at',
        'rejection_reason',
        'description',
        //        'uploads',

    ];
    protected $dates = [
        'cover_note_start_date',
        'cover_note_end_date',
        'approved_at',      // ADD THIS
        'rejected_at',      // ADD THIS
    ];

    // -------------------------
    // Relations
    // -------------------------

    public function coverage()
    {
        return $this->belongsTo(Coverage::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function coverNoteType()
    {
        return $this->belongsTo(CoverNoteType::class, 'cover_note_type_id', 'id');
    }

    public function coverNoteDuration()
    {
        return $this->belongsTo(CoverNoteDuration::class, 'cover_note_duration_id', 'id');
    }

    public function paymentMode()
    {
        return $this->belongsTo(PaymentMode::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }


    // Endorsement Type
    public function endorsementType()
    {
        return $this->belongsTo(EndorsementType::class);
    }

    // Motor Relations
    public function motorCategory()
    {
        return $this->belongsTo(MotorCategory::class);
    }

    public function motorType()
    {
        return $this->belongsTo(MotorType::class);
    }

    public function motorUsage()
    {
        return $this->belongsTo(MotorUsage::class);
    }

    public function ownerCategory()
    {
        return $this->belongsTo(OwnerCategory::class);
    }


    // Auth User Relations
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function quotationEndorsements()
    {
        return $this->hasMany(QuotationEndorsement::class);
    }

    public function quotationFleetDetails()
    {
        return $this->hasMany(QuotationFleetDetail::class);
    }

    public function insuarer()
{

    return $this->belongsTo(Insuarer::class, 'insuarer_id');
}

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }
}
