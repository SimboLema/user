<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'numbers',
        'successful',
        'request_id',
        'code',
        'message',
        'valid',
        'invalid',
        'duplicates',
        'created_by',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
