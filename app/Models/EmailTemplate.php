<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'content',
        'variables',
        'created_by',
        'is_active',
        'archive',
    ];

    protected $casts = [
        'variables' => 'array',
        'archive' => 'boolean',
    ];

    public function users()
    {
        return $this->hasMany(NotificationUser::class,'template_id', 'id')->where('type','email')->where('archive',0);
    }
}
