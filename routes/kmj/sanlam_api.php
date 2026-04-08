<?php

use App\Http\Controllers\SanlamController;
use Illuminate\Support\Facades\Route;

Route::post('/sanlam/nonfleet', [SanlamController::class, 'submitNonFleet']);
Route::post('/sanlam/fleet', [SanlamController::class, 'submitFleet']);
Route::post('/sanlam/callback', [SanlamController::class, 'sanlamCallback']);
Route::get('/sanlam/callback', [SanlamController::class, 'getCallback']);

