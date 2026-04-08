<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PusherSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'api_id',
        'key',
        'secret',
        'cluster',
        'channel',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'archive'
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
