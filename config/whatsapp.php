<?php

return [
    'api_key' => env('WHATSAPP_API_KEY'),
    'base_url' => env('WHATSAPP_API_BASE_URL', 'https://waba-v2.360dialog.io'),
    'phone_number' => env('WHATSAPP_BUSINESS_PHONE_NUMBER'),
];