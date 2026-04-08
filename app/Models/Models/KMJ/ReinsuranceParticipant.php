<?php

namespace App\Models\Models\KMJ;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReinsuranceParticipant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'reinsurance_id',
        'participant_code',
        'participant_type_id',
        'reinsurance_form_id',
        'reinsurance_type_id',
        'rebroker_code',
        'brokerage_commission',
        'reinsurance_commission',
        'premium_share',
        'participation_date',
    ];

    public function reinsurance()
    {
        return $this->belongsTo(Reinsurance::class);
    }

    public function participantType()
    {
        return $this->belongsTo(ParticipantType::class, 'participant_type_id');
    }

    public function form()
    {
        return $this->belongsTo(ReinsuranceForm::class, 'reinsurance_form_id');
    }

    public function type()
    {
        return $this->belongsTo(ReinsuranceType::class, 'reinsurance_type_id');
    }
}
