<?php

use App\Http\Controllers\StrategiesInsuranceController;
use Illuminate\Support\Facades\Route;

Route::post('/insurance/assured', [StrategiesInsuranceController::class, 'createAssured']);
Route::post('/insurance/policy', [StrategiesInsuranceController::class, 'createPolicy']);
Route::post('/insurance/full-strategy', [StrategiesInsuranceController::class, 'fullStrategyFlow']);
Route::post('/strategy/callback', [StrategiesInsuranceController::class, 'callback']);
Route::get('/strategy/callback', [StrategiesInsuranceController::class, 'getCallback']);


