<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'system_name',
        'two_factor_auth',
        'currency',
        'system_logo',
        'system_favicon',
        'email_notifications',
        'sms_notifications',
        'unit_id',
        'created_by',
    ];



    // Function to format amount with currency symbol
    public static function number_format($amount)
    {
        // Retrieve the active settings entry
        $setting = self::first();

        // Check the currency and get the corresponding symbol
        $currency = $setting->currency ?? "TZS"; // Default to 'Tsh' if not set
        $currencySymbol = self::getCurrencySymbol($currency);

        // Format the amount with the currency symbol
        return  number_format($amount, 2).' '.$currencySymbol;
    }

    // Helper function to return the symbol based on the currency name
    private static function getCurrencySymbol($currency)
    {
        switch (strtoupper($currency)) {
            case 'USD':
                return '$'; // Dollar symbol
            case 'EUR':
                return '€'; // Euro symbol
            case 'TSH':
            case 'TZS':
                return 'Tsh'; // Tanzanian Shilling symbol
            default:
                return 'Tsh'; // If currency is unknown, return empty string
        }
    }
}
