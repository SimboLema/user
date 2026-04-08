<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmtpSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_name',
        'sender_email',
        'smtp_driver',
        'smtp_host',
        'smtp_username',
        'smtp_password',
        'smtp_encryption',
        'smtp_port',
        'created_by'
    ];
}
