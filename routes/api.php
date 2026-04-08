<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OperationController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\Api\AuthController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



Route::post('login', [AuthController::class,'login']);
Route::get('resendToken', [AuthenticationController::class,'resendToken']);
Route::get('verifyToken', [AuthenticationController::class,'verifyToken']);
Route::get('sendPasswordResetLink', [AuthenticationController::class,'sendPasswordResetLink']);




// collection center
Route::prefix('collection_center')->group(function () {
    Route::post('save', [CompanyController::class, 'saveCollectionCenter']);
});
Route::prefix('collection_center_user')->group(function () {
    Route::post('save', [CompanyController::class, 'saveCollectionCenterUser']);
});

// waste_picker
Route::prefix('waste_picker')->group(function () {
    Route::post('save', [CompanyController::class, 'saveWastePicker']);
});

// recycling facility
Route::prefix('recycling_facility')->group(function () {
    Route::post('save', [CompanyController::class, 'saveRecyclingFacility']);
});
Route::prefix('recycling_facility_user')->group(function () {
    Route::post('save', [CompanyController::class, 'saveRecyclingFacilityUser']);
});

// producer
Route::prefix('producer')->group(function () {
    Route::post('save', [CompanyController::class, 'saveProducer']);
});
Route::prefix('producer_user')->group(function () {
    Route::post('save', [CompanyController::class, 'saveProducerUser']);
});


// location routes
Route::prefix('location')->group(function() {
    Route::get('getCountry', [SettingController::class, 'getCountry']);
    Route::get('getCountryRegion/{id}', [SettingController::class, 'getCountryRegion']);
    Route::get('getRegionDistrict/{id}', [SettingController::class, 'getRegionDistrict']);
    Route::get('getDistrictWard/{id}', [SettingController::class, 'getDistrictWard']);
    Route::post('saveDeviceLocation', [SettingController::class, 'saveDeviceLocation']);
});

// more
// unit
Route::prefix('unit')->group(function () {
    Route::get('getUnit', [ConfigurationController::class, 'getUnit']);
});



Route::middleware('auth:sanctum')->group(function () {

    Route::get('getDashboard', [DashboardController::class, 'getDashboard']);
    Route::get('getPermission', [SettingController::class, 'getPermission']);

    // waste_picker
    Route::prefix('waste_picker')->group(function () {
        Route::get('getWastePicker', [CompanyController::class, 'getWastePicker']);

    });

    // user
    Route::prefix('user')->group(function () {
        Route::get('getUserRow/{id}', [UserController::class, 'getUserRow']);
    });


    // collection center
    Route::prefix('collection_center')->group(function () {
        Route::get('getCollectionCenterUser', [CompanyController::class, 'getCollectionCenterUser']);
        Route::get('getCollectionCenter', [CompanyController::class, 'getCollectionCenter']);
    });

    // recycling facility
    Route::prefix('recycling_facility')->group(function () {
        Route::get('getRecyclingFacility', [CompanyController::class, 'getRecyclingFacility']);
        Route::get('getRecyclingFacilityUser', [CompanyController::class, 'getRecyclingFacilityUser']);
    });


    // producer
    Route::prefix('producer')->group(function () {
        Route::get('getProducer', [CompanyController::class, 'getProducer']);
        Route::get('getProducerUser', [CompanyController::class, 'getProducerUser']);
        Route::post('saveProducer', [CompanyController::class, 'saveProducer']);
        Route::post('saveProducerUser', [CompanyController::class, 'saveProducerUser']);
    });

    // Waste collection
    Route::prefix('waste_collection')->group(function () {
        Route::get('getWasteCollection', [OperationController::class, 'getWasteCollection']);
        Route::get('getWasteCollectionRow/{id}', [OperationController::class, 'getWasteCollectionRow']);
        Route::post('payment', [OperationController::class, 'saveWasteCollectionPayment']);
        Route::post('save', [OperationController::class, 'saveWasteCollection']);
    });

    // Recycling Waste collection
    Route::prefix('recycling_waste_collection')->group(function () {
        Route::get('getRecyclingWasteCollection', [OperationController::class, 'getRecyclingWasteCollection']);
        Route::get('getRecyclingWasteCollectionRow/{id}', [OperationController::class, 'getRecyclingWasteCollectionRow']);
        Route::post('payment', [OperationController::class, 'saveRecyclingWasteCollectionPayment']);
        Route::post('save', [OperationController::class, 'saveRecyclingWasteCollection']);
    });

    // waste type
    Route::prefix('waste_type')->group(function () {
        Route::get('getWasteType', [ConfigurationController::class, 'getWasteType']);
        Route::get('getSubWasteType', [ConfigurationController::class, 'getSubWasteType']);
    });

    // color
    Route::prefix('color')->group(function () {
        Route::get('getColor', [SettingController::class, 'getColor']);
    });

    // inventory
    Route::prefix('inventory')->group(function () {
        Route::get('getInventoryBalance', [InventoryController::class, 'getInventoryBalance']);
    });

    // product
    Route::prefix('product')->group(function () {
        Route::get('getProduct', [SettingController::class, 'getProduct']);
        Route::post('save', [SettingController::class, 'saveProduct']);
    });

    // recycling facility
    Route::prefix('recycling_material')->group(function () {
        Route::get('getRecyclingMaterial', [OperationController::class, 'getRecyclingMaterial']);
        Route::post('save', [OperationController::class, 'saveRecyclingMaterial']);
    });


    Route::post('logout', [AuthController::class, 'logout']);
});

require_once __DIR__.'/kmj/api.php';
include_once __DIR__.'/kmj/Strategies_api.php';
include_once __DIR__.'/kmj/sanlam_api.php';


