<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'template_id',
        'user_id',
        'type',
        'archive',
    ];

    public function user()
    {
        return $this->hasOne(User::class,'id', 'user_id');
    }

    public function template()
    {
        return $this->hasOne(NotificationTemplate::class,'id', 'template_id');
    }
}
