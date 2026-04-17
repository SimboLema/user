<?php

namespace App\Models\Models\KMJ;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Insuarer extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'Company_code',
        'Insuarer_code',
        'auto_approval_limit',
    ];

    protected $hidden = [
        'password',
    ];

    public function quotations()
{
    return $this->hasMany(\App\Models\Models\KMJ\Quotation::class);
}

}
