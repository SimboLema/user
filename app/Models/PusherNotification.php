<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PusherNotification extends Model
{
    use HasFactory;

    protected $fillable=[
        'title',
        'message',
        'date',
        'time',
        'is_clicked',
        'is_opened',
        'is_delivered',
        'user_id',
        'redirect_link',
        'created_by',
        'archive',
    ];
}
