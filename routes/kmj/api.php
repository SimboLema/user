<?php

use App\Http\Controllers\Api\DownloadCertificateController;
use App\Http\Controllers\KMJ\Api\ClaimController;
use App\Http\Controllers\KMJ\Api\CovernoteController;
use App\Http\Controllers\KMJ\Api\FleetCoverNoteController;
use App\Http\Controllers\KMJ\Api\PolicySubmissionController;
use App\Http\Controllers\KMJ\Api\ReinsuranceController;
use App\Http\Controllers\KMJ\Api\TiraCallbackController;
use App\Http\Controllers\KMJ\DispatchMotorVerificationController;
use App\Http\Controllers\KMJ\RenewalController;
use App\Http\Controllers\TestEmailController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KMJ\AdminInsuarerController;



// 2. Callback Handler
Route::post('/tiramis/callback', [TiraCallbackController::class, 'tiraCallbackHandler']);
Route::post('/motor-fleet-cover-notes', [FleetCoverNoteController::class, 'fleetCoverNotes']);
Route::post('/reinsurance', [ReinsuranceController::class, 'reinsuranceSubmission']);
Route::post('/policy-submission', [PolicySubmissionController::class, 'policySubmission']);
Route::post('/claim-notification-submission', [ClaimController::class, 'claimNotificationSubmission']);
Route::post('/claim-intimation-submission', [ClaimController::class, 'claimIntimationSubmission']);
Route::post('/claim-assessment-submission', [ClaimController::class, 'claimAssessmentSubmission']);
Route::post('/claim-discharge-voucher-submission', [ClaimController::class, 'claimDischargeVoucherSubmission']);
Route::post('/claim-payment-submission', [ClaimController::class, 'claimPaymentSubmission']);
Route::post('/claim-rejection-submission', [ClaimController::class, 'claimRejectionSubmission']);
Route::post('/covernote-verification-submission', [CovernoteController::class, 'coverNoteVerificationSubmission']);
Route::post('/dispatch-motor-verification', [DispatchMotorVerificationController::class, 'dispatchMotorVerification']);

//ADMIN NA INSUARER
        Route::get('/insurers', [AdminInsuarerController::class, 'index']);   // list insurers
        Route::post('/insurers', [AdminInsuarerController::class, 'store']);  // add insurer
        Route::get('/insurers/{id}', [AdminInsuarerController::class, 'show']); // view single insurer
        Route::put('/insurers/{id}', [AdminInsuarerController::class, 'update']); // update insurer
        Route::delete('/insurers/{id}', [AdminInsuarerController::class, 'destroy']);

Route::post('/test-time', [DispatchMotorVerificationController::class, 'testTime']);

Route::get('/download-certificates', [DownloadCertificateController::class, 'downloadAll'])->name('download.certificates');
Route::get('/renewals', [RenewalController::class, 'renewals'])->name('kmj.renewals');

Route::post('/send-test-email', [TestEmailController::class, 'send']);

include_once('Strategies_api.php');
include_once('mua_api.php');


