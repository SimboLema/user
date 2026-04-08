<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\OperationController;
use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\CompanyController;

//SIMBO
use App\Http\Controllers\KMJ\AdminDashboardController;
use App\Http\Controllers\KMJ\AdminInsuarerController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [AuthenticationController::class,'indexPage']);
Route::get('login', [AuthenticationController::class,'login'])->name('login');
Route::get('register', [AuthenticationController::class,'register'])->name('register');
Route::get('token_login', [AuthenticationController::class,'token_login'])->name('token_login');
Route::get('forgot_password', [AuthenticationController::class,'forgot_password']);
Route::post('validateAccount', [AuthenticationController::class,'validateAccount']);
Route::post('sendPasswordResetLink', [AuthenticationController::class,'sendPasswordResetLink']);
Route::get('reset_password', [AuthenticationController::class, 'reset_password']);
Route::post('resetPassword', [AuthenticationController::class, 'resetPassword']);
Route::post('verifyToken', [AuthenticationController::class, 'verifyToken']);
Route::post('resendToken', [AuthenticationController::class, 'resendToken']);
Route::get('page_not_found', [AuthenticationController::class,'page_not_found']);
Route::get('page_not_authorized', [AuthenticationController::class,'page_not_authorized']);

Route::prefix('location')->group(function() {
    Route::get('getCountryRegion/{id}', [SettingController::class, 'getCountryRegion']);
    Route::get('getRegionDistrict/{id}', [SettingController::class, 'getRegionDistrict']);
    Route::get('getDistrictWard/{id}', [SettingController::class, 'getDistrictWard']);
});

// collection center
Route::prefix('collection_center')->group(function () {
    Route::post('save', [CompanyController::class, 'saveCollectionCenter']);
});

// recycling facility
Route::prefix('recycling_facility')->group(function () {
    Route::post('save', [CompanyController::class, 'saveRecyclingFacility']);
});

// producer
Route::prefix('producer')->group(function () {
    Route::post('save', [CompanyController::class, 'saveProducer']);
});


Route::middleware(['auth'])->group(function () {
    Route::get('/impersonate/{id}', function($id) {
        if (auth()->user() && auth()->user()->role == 1) {
            auth()->loginUsingId($id);
            // return redirect('/dashboard');
            return redirect('/kmj');
        }
        abort(403, 'Unauthorized');
    })->name('impersonate');

    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('dashboard/getDashboardSummary', [DashboardController::class, 'getDashboardSummary']);
    Route::get('notification', [DashboardController::class, 'notification'])->name('notification');
    Route::get('setting', [SettingController::class, 'index']);
    Route::get('generateUniqueNumber', [SettingController::class, 'generateUniqueNumber']);

    // setting
    Route::prefix('setting')->group(function(){
        Route::get('/', [SettingController::class, 'index'])->name('setting.index');
        Route::get('view', [SettingController::class, 'view']);
        Route::post('saveSetting', [SettingController::class, 'saveSetting']);
    });

    // smtp setting
    Route::prefix('smtp')->group(function(){
        Route::get('/', [SettingController::class, 'smtp'])->name('smtp.index');
        Route::get('smtp_view', [SettingController::class, 'smtp_view']);
        Route::post('saveSmtpSetting', [SettingController::class, 'saveSmtpSetting']);
    });

    // sms setting
    Route::prefix('sms')->group(function(){
        Route::get('/', [SettingController::class, 'sms'])->name('sms.index');
        Route::get('sms_view', [SettingController::class, 'sms_view']);
        Route::post('saveBeamSetting', [SettingController::class, 'saveBeamSetting']);
    });

    // pusher setting
    Route::prefix('pusher')->group(function(){
        Route::get('/', [SettingController::class, 'pusher'])->name('pusher.index');
        Route::get('pusher_view', [SettingController::class, 'pusher_view']);
        Route::post('savePusherSetting', [SettingController::class, 'savePusherSetting']);
    });

    Route::prefix('payment-status')->group(function() {
        Route::get('/', [SettingController::class, 'payment_status'])->name('payment_status.index');
        Route::get('payment_status_view', [SettingController::class, 'payment_status_view']);
        Route::get('editPaymentStatus/{id}', [SettingController::class, 'editPaymentStatus']);
        Route::get('deletePaymentStatus/{id}', [SettingController::class, 'deletePaymentStatus']);
        Route::post('savePaymentStatus', [SettingController::class, 'savePaymentStatus']);
    });


    // user
    Route::prefix('user')->group(function(){
        Route::get('/', [UserController::class, 'index'])->name('user.index');
        Route::get('show/{id}', [UserController::class, 'show']);
        Route::get('view', [UserController::class, 'view']);
        Route::get('editUser/{id}', [UserController::class, 'editUser']);
        Route::get('deleteUser/{id}', [UserController::class, 'deleteUser']);
        Route::get('updateUserStatus', [UserController::class, 'updateUserStatus']);
        Route::post('saveUser', [UserController::class, 'saveUser']);
        Route::post('saveProfile', [UserController::class, 'saveProfile']);
        Route::post('changePassword', [UserController::class, 'changePassword']);
    });

    // role
    Route::prefix('role')->group(function(){
        Route::get('/', [RoleController::class, 'index'])->name('role.index');
        Route::get('view', [RoleController::class, 'view']);
        Route::get('editRole/{id}', [RoleController::class, 'editRole']);
        Route::get('deleteRole/{id}', [RoleController::class, 'deleteRole']);
        Route::post('saveRole', [RoleController::class, 'saveRole']);
    });





    Route::prefix('audit_trail')->group(function(){
        Route::get('/', [UserController::class, 'audit_trail'])->name('audit_trail.index');
        Route::get('audit_trail_view', [UserController::class, 'audit_trail_view']);
    });



    // notification_template setting
    Route::prefix('notification_template')->group(function(){
        Route::get('/', [SettingController::class, 'notification_template'])->name('notification_template.index');
        Route::get('notification_template_view', [SettingController::class, 'notification_template_view']);
        Route::post('saveNotificationTemplate', [SettingController::class, 'saveNotificationTemplate']);
    });

    // email_template setting
    Route::prefix('email_template')->group(function(){
        Route::get('/', [SettingController::class, 'email_template'])->name('email_template.index');
        Route::get('email_template_view', [SettingController::class, 'email_template_view']);
        Route::post('saveEmailTemplate', [SettingController::class, 'saveEmailTemplate']);
    });

    // Country
    Route::prefix('country')->group(function () {
        Route::get('/', [SettingController::class, 'country'])->name('country.index');
        Route::get('view', [SettingController::class, 'country_view']);
        Route::get('editCountry/{id}', [SettingController::class, 'editCountry']);
        Route::get('deleteCountry/{id}', [SettingController::class, 'deleteCountry']);
        Route::post('saveCountry', [SettingController::class, 'saveCountry']);
    });

    // color
    Route::prefix('color')->group(function () {
        Route::get('/', [SettingController::class, 'color'])->name('color.index');
        Route::get('view', [SettingController::class, 'color_view']);
        Route::get('edit/{id}', [SettingController::class, 'editColor']);
        Route::get('delete/{id}', [SettingController::class, 'deleteColor']);
        Route::post('save', [SettingController::class, 'saveColor']);
    });

    // product
    Route::prefix('product')->group(function () {
        Route::get('/', [SettingController::class, 'product'])->name('product.index');
        Route::get('view', [SettingController::class, 'product_view']);
        Route::get('edit/{id}', [SettingController::class, 'editProduct']);
        Route::get('delete/{id}', [SettingController::class, 'deleteProduct']);
        Route::post('save', [SettingController::class, 'saveProduct']);
    });

    // recycled material
    Route::prefix('recycling_material')->group(function () {
        Route::get('/', [OperationController::class, 'recycling_material'])->name('recycling_material.index');
        Route::get('view', [OperationController::class, 'recycling_material_view']);
        Route::get('edit/{id}', [OperationController::class, 'editRecyclingMaterial']);
        Route::get('delete/{id}', [OperationController::class, 'deleteRecyclingMaterial']);
        Route::get('show/{id}', [OperationController::class, 'recycling_material_details']);
        Route::post('save', [OperationController::class, 'saveRecyclingMaterial']);
    });

    // Region
    Route::prefix('region')->group(function () {
        Route::get('/', [SettingController::class, 'region'])->name('region.index');
        Route::get('view', [SettingController::class, 'region_view']);
        Route::get('editRegion/{id}', [SettingController::class, 'editRegion']);
        Route::get('deleteRegion/{id}', [SettingController::class, 'deleteRegion']);
        Route::post('saveRegion', [SettingController::class, 'saveRegion']);
    });

    // District
    Route::prefix('district')->group(function () {
        Route::get('/', [SettingController::class, 'district'])->name('district.index');
        Route::get('view', [SettingController::class, 'district_view']);
        Route::get('editDistrict/{id}', [SettingController::class, 'editDistrict']);
        Route::get('deleteDistrict/{id}', [SettingController::class, 'deleteDistrict']);
        Route::post('saveDistrict', [SettingController::class, 'saveDistrict']);
    });

    // Ward
    Route::prefix('ward')->group(function () {
        Route::get('/', [SettingController::class, 'ward'])->name('ward.index');
        Route::get('view', [SettingController::class, 'ward_view']);
        Route::get('editWard/{id}', [SettingController::class, 'editWard']);
        Route::get('deleteWard/{id}', [SettingController::class, 'deleteWard']);
        Route::post('saveWard', [SettingController::class, 'saveWard']);
    });

    // unit
    Route::prefix('unit')->group(function () {
        Route::get('/', [ConfigurationController::class, 'unit'])->name('unit.index');
        Route::get('view', [ConfigurationController::class, 'unit_view']);
        Route::get('editUnit/{id}', [ConfigurationController::class, 'editUnit']);
        Route::get('deleteUnit/{id}', [ConfigurationController::class, 'deleteUnit']);
        Route::post('saveUnit', [ConfigurationController::class, 'saveUnit']);
    });

    // waste type
    Route::prefix('waste_type')->group(function () {
        Route::get('/', [ConfigurationController::class, 'waste_type'])->name('waste_type.index');
        Route::get('view', [ConfigurationController::class, 'waste_type_view']);
        Route::get('editWasteType/{id}', [ConfigurationController::class, 'editWasteType']);
        Route::get('deleteWasteType/{id}', [ConfigurationController::class, 'deleteWasteType']);
        Route::get('getSubWasteType', [ConfigurationController::class, 'getSubWasteType']);
        Route::post('saveWasteType', [ConfigurationController::class, 'saveWasteType']);
    });

    // waste source
    Route::prefix('waste_source')->group(function () {
        Route::get('/', [ConfigurationController::class, 'waste_source'])->name('waste_source.index');
        Route::get('view', [ConfigurationController::class, 'waste_source_view']);
        Route::get('editWasteSource/{id}', [ConfigurationController::class, 'editWasteSource']);
        Route::get('deleteWasteSource/{id}', [ConfigurationController::class, 'deleteWasteSource']);
        Route::post('saveWasteSource', [ConfigurationController::class, 'saveWasteSource']);
    });

    // waste source
    Route::prefix('illegal_dumping')->group(function () {
        Route::get('/', [ConfigurationController::class, 'illegal_dumping'])->name('illegal_dumping.index');
        Route::get('view', [ConfigurationController::class, 'illegal_dumping_view']);
        Route::get('editIllegalDumping/{id}', [ConfigurationController::class, 'editIllegalDumping']);
        Route::get('deleteIllegalDumping/{id}', [ConfigurationController::class, 'deleteIllegalDumping']);
        Route::post('saveIllegalDumping', [ConfigurationController::class, 'saveIllegalDumping']);
    });

    // collection center
    Route::prefix('collection_center')->group(function () {
        Route::get('/', [CompanyController::class, 'collection_center'])->name('collection_center.index');
        Route::get('view', [CompanyController::class, 'collection_center_view']);
        Route::get('edit/{id}', [CompanyController::class, 'editCollectionCenter']);
        Route::get('delete/{id}', [CompanyController::class, 'deleteCollectionCenter']);
    });

    // collection
    Route::prefix('waste_collection')->group(function () {
        Route::get('/', [OperationController::class, 'waste_collection'])->name('waste_collection.index');
        Route::get('view', [OperationController::class, 'waste_collection_view']);
        Route::get('show/{id}', [OperationController::class, 'waste_collection_details'])->name('waste_collection.show');
        Route::get('edit/{id}', [OperationController::class, 'editWasteCollection']);
        Route::get('delete/{id}', [OperationController::class, 'deleteWasteCollection']);
        Route::post('payment', [OperationController::class, 'saveWasteCollectionPayment']);
        Route::post('save', [OperationController::class, 'saveWasteCollection']);
    });

    // collection
    Route::prefix('recycling_waste_collection')->group(function () {
        Route::get('/', [OperationController::class, 'recycling_waste_collection'])->name('recycling_waste_collection.index');
        Route::get('view', [OperationController::class, 'recycling_waste_collection_view']);
        Route::get('show/{id}', [OperationController::class, 'recycling_waste_collection_details'])->name('recycling_waste_collection.show');
        Route::get('edit/{id}', [OperationController::class, 'editRecyclingWasteCollection']);
        Route::get('delete/{id}', [OperationController::class, 'deleteRecyclingWasteCollection']);
        Route::post('payment', [OperationController::class, 'saveRecyclingWasteCollectionPayment']);
        Route::post('save', [OperationController::class, 'saveRecyclingWasteCollection']);
    });

    // inventory
    Route::prefix('inventory_balance')->group(function () {
        Route::get('/', [InventoryController::class, 'inventory_balance'])->name('inventory_balance.index');
        Route::get('view', [InventoryController::class, 'inventory_balance_view']);
    });

    // inventory_adjustment
    Route::prefix('inventory_adjustment')->group(function () {
        Route::get('/', [InventoryController::class, 'inventory_adjustment'])->name('inventory_adjustment.index');
        Route::get('view', [InventoryController::class, 'inventory_adjustment_view']);
        Route::post('save', [InventoryController::class, 'saveInventoryAdjustment']);
    });

    // collection center user
    Route::prefix('collection_center_user')->group(function () {
        Route::get('/', [CompanyController::class, 'collection_center_user'])->name('collection_center_user.index');
        Route::get('view', [CompanyController::class, 'collection_center_user_view']);
        Route::get('edit/{id}', [CompanyController::class, 'editCollectionCenterUser']);
        Route::get('delete/{id}', [CompanyController::class, 'deleteCollectionCenterUser']);
        Route::post('save', [CompanyController::class, 'saveCollectionCenterUser']);
    });

    // producer
    Route::prefix('producer')->group(function () {
        Route::get('/', [CompanyController::class, 'producer'])->name('producer.index');
        Route::get('view', [CompanyController::class, 'producer_view']);
        Route::get('edit/{id}', [CompanyController::class, 'editProducer']);
        Route::get('delete/{id}', [CompanyController::class, 'deleteProducer']);
    });

    // producer user
    Route::prefix('producer_user')->group(function () {
        Route::get('/', [CompanyController::class, 'producer_user'])->name('producer_user.index');
        Route::get('view', [CompanyController::class, 'producer_user_view']);
        Route::get('edit/{id}', [CompanyController::class, 'editProducerUser']);
        Route::get('delete/{id}', [CompanyController::class, 'deleteProducerUser']);
        Route::post('save', [CompanyController::class, 'saveProducerUser']);
    });

     // waste picker user
    Route::prefix('waste_picker')->group(function () {
        Route::get('/', [CompanyController::class, 'waste_picker'])->name('waste_picker.index');
        Route::get('view', [CompanyController::class, 'waste_picker_view']);
        Route::get('edit/{id}', [CompanyController::class, 'editWastePicker']);
        Route::get('delete/{id}', [CompanyController::class, 'deleteWastePicker']);
        Route::post('save', [CompanyController::class, 'saveWastePicker']);
    });

    // recycling facility
    Route::prefix('recycling_facility')->group(function () {
        Route::get('/', [CompanyController::class, 'recycling_facility'])->name('recycling_facility.index');
        Route::get('view', [CompanyController::class, 'recycling_facility_view']);
        Route::get('edit/{id}', [CompanyController::class, 'editRecyclingFacility']);
        Route::get('delete/{id}', [CompanyController::class, 'deleteRecyclingFacility']);
    });

    // recycling_facility user
    Route::prefix('recycling_facility_user')->group(function () {
        Route::get('/', [CompanyController::class, 'recycling_facility_user'])->name('recycling_facility_user.index');
        Route::get('view', [CompanyController::class, 'recycling_facility_user_view']);
        Route::get('edit/{id}', [CompanyController::class, 'editRecyclingFacilityUser']);
        Route::get('delete/{id}', [CompanyController::class, 'deleteRecyclingFacilityUser']);
        Route::post('save', [CompanyController::class, 'saveRecyclingFacilityUser']);
    });








    Route::get('logout', [AuthenticationController::class,'logout'])->name('logout');
});

require_once __DIR__ . '/kmj/web.php';
