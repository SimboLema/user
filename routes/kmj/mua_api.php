<?php

use App\Http\Controllers\MUAInsuranceController;
use Illuminate\Support\Facades\Route;

Route::prefix('mua')->group(function () {
    Route::post('policy/new', [MUAInsuranceController::class, 'createPolicy']);
    Route::post('policy/trareceipt', [MUAInsuranceController::class, 'traReceipt']);
    Route::post('policy/newvehverify', [MUAInsuranceController::class, 'newVehVerify']);
    Route::get('policy/vehverify', [MUAInsuranceController::class, 'vehVerify']);
    Route::post('policy/cbotreceipt', [MUAInsuranceController::class, 'cbotReceipt']);
    Route::post('/callback', [MUAInsuranceController::class, 'callback']);
    Route::get('/callback', [MUAInsuranceController::class, 'getCallback']);
});
