<?php

namespace App\Models\Models\KMJ;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'quotation_id',
        'name',
        'file_path',
        'uploaded_by',
    ];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'quotation_id', 'id');
    }

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by', 'id');
    }
}
