<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsAppMessage extends Model
{
    protected $fillable = [
        'message_id',
        'from_phone',
        'to_phone',
        'type',
        'content',
        'direction',
        'status',
        'timestamp',
        'status_updated_at',
        'errors',
        'raw_data',
    ];

    protected $casts = [
        'timestamp' => 'datetime',
        'status_updated_at' => 'datetime',
        'errors' => 'array',
    ];

    /**
     * Scope to get inbound messages
     */
    public function scopeInbound($query)
    {
        return $query->where('direction', 'inbound');
    }

    /**
     * Scope to get outbound messages
     */
    public function scopeOutbound($query)
    {
        return $query->where('direction', 'outbound');
    }

    /**
     * Scope to get messages from a specific phone number
     */
    public function scopeFromPhone($query, string $phoneNumber)
    {
        return $query->where('from_phone', $phoneNumber);
    }
}