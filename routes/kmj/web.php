<?php

use App\Http\Controllers\KMJ\AdminInsuarerController;
use App\Http\Controllers\KMJ\AgentController;
use App\Http\Controllers\KMJ\Api\CovernoteController;
use App\Http\Controllers\KMJ\Api\PolicySubmissionController;
use App\Http\Controllers\KMJ\Api\QuotationCoverNoteController;
use App\Http\Controllers\KMJ\Api\QuotationEndorsementController;
use App\Http\Controllers\KMJ\Api\ReinsuranceController;
use App\Http\Controllers\KMJ\BranchController;
use App\Http\Controllers\KMJ\CancellationController;
use App\Http\Controllers\KMJ\ClaimController;
use App\Http\Controllers\KMJ\CommissionController;
use App\Http\Controllers\KMJ\CustomerController;
use App\Http\Controllers\KMJ\DownloadController;
use App\Http\Controllers\KMJ\FleetMotorController;
use App\Http\Controllers\KMJ\KMJServiceController;
use App\Http\Controllers\KMJ\MessageController;
use App\Http\Controllers\KMJ\NotificationController;
use App\Http\Controllers\KMJ\ProductController;
use App\Http\Controllers\KMJ\ProfomaController;
use App\Http\Controllers\KMJ\QuotationController;
use App\Http\Controllers\KMJ\RegionController;
use App\Http\Controllers\KMJ\RenewalController;
use App\Http\Controllers\KMJ\ReportController;
use App\Http\Controllers\KMJ\RisknoteController;
use App\Http\Controllers\KMJ\SendTiraController;
use App\Http\Controllers\KMJ\SettingController;
use App\Http\Controllers\KMJ\TransactionController;
use Illuminate\Support\Facades\Route;

use App\Mail\QuotationCreatedMail;
use App\Models\Models\KMJ\Quotation;
use Illuminate\Support\Facades\Mail;





Route::middleware(['auth'])->group(function () {

    //KMJ Routes
    Route::prefix('dash')->group(function () {

       Route::get('/', [KMJServiceController::class, 'index'])->name('kmj.index');
       
       // Route::get('/', function() { dd ( file_get_contents(public_path('tiramis_certs/private_key_new.pem')));});//[KMJServiceController::class, 'index'])->name('kmj.index');

        //ADMIN NA INSUARER
        Route::prefix('admin')->name('admin.')->group(function () {
            Route::resource('insuarers', AdminInsuarerController::class);
        });


        // Add other quotation
        Route::prefix('quotation')->group(function () {
            Route::get('/', [QuotationController::class, 'index'])->name('kmj.quotation');
            Route::get('/report', [QuotationController::class, 'quotationReport'])->name('kmj.quotation.report');

            Route::get('/create', [QuotationController::class, 'getQuotation'])->name('kmj.quotation.getQuotation');
            Route::post('/', [QuotationController::class, 'store'])->name('kmj.quotation.store');

            //tabs
            Route::get('/{id}/covernote', [QuotationController::class, 'covernote'])->name('kmj.quotation.covernote');
            Route::post('/{id}/RenewCoverNote', action: [QuotationCoverNoteController::class, 'renewCoverNote'])
                ->name('quotation.RenewCoverNote');
            Route::get('/{id}/payment', [QuotationController::class, 'payment'])->name('kmj.quotation.payment');
            Route::get('/{id}/transaction', [QuotationController::class, 'transaction'])->name('kmj.quotation.transaction');

            Route::get('/{id}/addons', [QuotationController::class, 'addons'])->name('kmj.quotation.addons');
            Route::post('/{id}/addons/save', [QuotationController::class, 'saveAddons'])  ->name('kmj.quotation.addons.save');

            Route::get('/{id}/customer', [QuotationController::class, 'customer'])->name('kmj.quotation.customer');
            Route::get('/{id}/motor-details', [QuotationController::class, 'motorDetails'])->name('kmj.quotation.motorDetails');
            Route::get('/{id}/documents', [QuotationController::class, 'documents'])->name('kmj.quotation.documents');
            Route::get('/{id}/endorsement', [QuotationController::class, 'endorsement'])->name('kmj.quotation.endorsement');
            Route::post('/{id}/makeEndorsement', [QuotationEndorsementController::class, 'makeEndorsement'])
                ->name('quotation.makeEndorsement');

            Route::get('/{id}/download/covernote', [QuotationController::class, 'downloadCoverNote'])->name('kmj.quotation.download.covernote');
            Route::get('/{id}/download/quotation', [QuotationController::class, 'downloadQuotation'])->name('kmj.quotation.download.quotation');
            Route::post('/preview/quotation/download', [QuotationController::class, 'previewQuotation'])->name('kmj.quotation.preview.download');
            Route::get('/{id}/download/payment/receipt', [QuotationController::class, 'downloadPayment'])->name('kmj.quotation.download.payment');



            Route::get('/{id}', [QuotationController::class, 'covernote'])->name('kmj.quotation.show');
            Route::put('/{id}', [QuotationController::class, 'update'])->name('kmj.quotation.update');
            Route::delete('/{id}', [QuotationController::class, 'destroy'])->name('kmj.quotation.destroy');
        });

        // Fleet Motor
        Route::get('/fleet-motor', [FleetMotorController::class, 'index'])->name('fleet.motor');
        Route::post('/fleet-motor', [FleetMotorController::class, 'store'])->name('kmj.quotation.fleet.store');
        Route::post('/quotation-create-fleet-detail/{id}', [FleetMotorController::class, 'createFleetDetail'])->name('quotation.create.fleet.detail');
        Route::get('/quotation/fleet/{id}/edit', [FleetMotorController::class, 'editView'])
            ->name('quoatation.fleet.edit');
        Route::get('/quotation/fleet/endorsement/{id}/view', [FleetMotorController::class, 'endorsementView'])
            ->name('quotation.fleet.endorsement.view');
        Route::put('/quotation/fleet/{id}/update', [FleetMotorController::class, 'update'])->name('quotation.fleet.update');

        Route::get('/quotation/view/{id}/edit', [FleetMotorController::class, 'editQuoation'])
            ->name('quoatation.view.edit');
        Route::put('/quotation/{id}/update', [FleetMotorController::class, 'updateQuotation'])->name('quotation.update');

        //customer
        // Route::get('/customer/{id}/edit', [CustomerController::class, 'customer'])->name('customer.edit');
        Route::put('/customer/{id}', [CustomerController::class, 'updateCustomer'])->name('customer.update');








        // insurance
        Route::get('/insurance/{id}/products', [QuotationController::class, 'getProducts']);
        Route::get('/product/{id}/coverages', [QuotationController::class, 'getCoverages']);
        Route::get('/regions/{regionId}/districts', [RegionController::class, 'getDistricts']);
        Route::get('/countries/{countryId}/regions', [RegionController::class, 'getRegions']);


        Route::get('/quotation/send-tira/{id}', [SendTiraController::class, 'sendToTira'])
            ->name('quotation.sendTira');

        Route::get('/quotation/send-tira/endorsement/{id}', [SendTiraController::class, 'sendToTiraEndorsements'])
            ->name('quotation.sendTira.Endorsement');

        Route::get('/quotations/search', [QuotationController::class, 'searchQuotation']);


        // Reinsurance
        Route::post('/reinsurance/store', [ReinsuranceController::class, 'store'])->name('kmj.reinsurance.store');
        Route::get('/reinsurances', [ReinsuranceController::class, 'index'])->name('kmj.reinsurance.index');
        Route::get('/reinsurances/{id}/sendTira', [ReinsuranceController::class, 'reinsuranceSubmission'])->name('kmj.reinsurance.sendTira');


        //Branch
        Route::get('/branches', [BranchController::class, 'getBranches'])->name('kmj.getBranches');
        Route::get('/branches/report', [BranchController::class, 'getBrancheReport'])->name('kmj.getBranches.report');
        Route::get('/branches/create', [BranchController::class,'create'])->name('kmj.createBranches');

        //Agent
        Route::get('/agents', [AgentController::class, 'getAgents'])->name('kmj.getAgents');
        Route::get('/agents/report', [AgentController::class, 'getAgentReport'])->name('kmj.getAgents.report');


        //Product
        Route::get('/products', [ProductController::class, 'getProducts'])->name('kmj.getProducts');

        // Renewals
        Route::get('/risknote', [RisknoteController::class, 'index'])->name('kmj.risknote');

        // Renewals
        Route::get('/renewals', [RenewalController::class, 'renewals'])->name('kmj.renewals');
        Route::get('/renewals/report', [RenewalController::class, 'renewalReport'])->name('kmj.renewals.report');


        Route::get('/transaction', [TransactionController::class, 'index'])->name('kmj.transaction');
        Route::get('/transaction/{id}', [TransactionController::class, 'show'])->name('transaction.show');


        Route::get('/report', [ReportController::class, 'index'])->name('kmj.report');

        Route::get('/customers', [CustomerController::class, 'index'])->name('kmj.customers');
        Route::get('/customers/search', [CustomerController::class, 'search'])->name('kmj.customers.search');
        Route::post('/customer/store', [CustomerController::class, 'customerStore'])->name('kmj.customer.store');
        Route::get('/customer/{id}/show', [CustomerController::class, 'customerShow'])->name('kmj.customer.show');

        Route::prefix('claim-notifications')->group(function () {
            Route::get('/', [ClaimController::class, 'index'])->name('kmj.claims');
            Route::get('/create/{quotation}', [ClaimController::class, 'quotationCreateNotification'])
                ->name('claimNotification.create');
            Route::post('/', [ClaimController::class, 'store'])->name('kmj.claimNotification.store');
            // Tabs

            Route::get('/notification/{id}', [ClaimController::class, 'notification'])->name('claim.notification');
            Route::get('/notification/{id}/sendTira', [ClaimController::class, 'claimNotificationSubmission'])->name('claim.notification.sendTira');


            Route::get('/intimation/{id}', [ClaimController::class, 'intimation'])->name('claim.intimation');
            Route::post('/claim-intimation/save', [ClaimController::class, 'saveClaimIntimation'])
                ->name('claim.intimation.save');
            Route::get('/intimation/{id}/sendTira', [ClaimController::class, 'claimIntimationSubmission'])->name('claim.intimation.sendTira');



            Route::get('/assessment/{id}', [ClaimController::class, 'assessment'])->name('claim.assessment');
            Route::post('/assessment/save', [ClaimController::class, 'saveClaimAssessment'])
                ->name('claim.assessment.save');
            Route::get('/assessment/{id}/sendTira', [ClaimController::class, 'claimAssessmentSubmission'])->name('claim.assessment.sendTira');



            Route::get('/discharge-voucher/{id}', [ClaimController::class, 'dischargeVoucher'])->name('claim.discharge.voucher');
            Route::post('/discharge-voucher/save', [ClaimController::class, 'saveDischargeVoucher'])
                ->name('claim.discharge.voucher.save');
            Route::get('/discharge-voucher/{id}/sendTira', [ClaimController::class, 'claimDischargeVoucherSubmission'])->name('claim.discharge.voucher.sendTira');

            Route::get('/payment/{id}', [ClaimController::class, 'payment'])->name('claim.payment');
            Route::post('/payment/save', [ClaimController::class, 'saveClaimPayment'])
                ->name('claim.payment.save');
            Route::get('/payment/{id}/sendTira', [ClaimController::class, 'claimPaymentSubmission'])->name('claim.payment.sendTira');


            Route::get('/rejection/{id}', [ClaimController::class, 'rejection'])->name('claim.rejection');
            Route::post('/rejection/save', [ClaimController::class, 'saveClaimRejection'])
                ->name('claim.rejection.save');
            Route::get('/rejection/{id}/sendTira', [ClaimController::class, 'claimRejectionSubmission'])->name('claim.rejection.sendTira');
        });

        Route::get('/policy/submission', [PolicySubmissionController::class, 'index'])->name('policy.submission.index');
        Route::post('/policy/submission/save', [PolicySubmissionController::class, 'savePolicySubmission'])->name('policy.submission.save');

        Route::get('/policy/submission/{id}/sendTira', [PolicySubmissionController::class, 'policySubmission'])->name('policy.submission.sendTira');

        Route::prefix('cover-note-verification')->group(function () {
            Route::get('/', [CovernoteController::class, 'index'])->name('covernote.verification.index');
            Route::post('/save', [CovernoteController::class, 'saveCoverNoteVerification'])->name('covernote.verification.save');
            Route::get('/send-tira/{id}', [CovernoteController::class, 'coverNoteVerificationSubmission'])->name('covernote.verification.sendTira');
        });




        Route::get('/proforma', [ProfomaController::class, 'index'])->name('kmj.proforma');

        Route::get('/downloads', [DownloadController::class, 'index'])->name('kmj.downloads');

        Route::get('/messages', [MessageController::class, 'index'])->name('kmj.messages');

        Route::get('/cancellation', [CancellationController::class, 'index'])->name('kmj.cancellation');

        Route::get('/commission', [CommissionController::class, 'index'])->name('kmj.commission');

        Route::get('/settings', [SettingController::class, 'index'])->name('kmj.settings');

        Route::get('/notifications', [NotificationController::class, 'index'])->name('kmj.notifications');
    });
});




 // PORTAL YA INSUARER


use App\Http\Controllers\KMJ\Insuarer\AuthController;
use App\Http\Controllers\KMJ\Insuarer\InsuarerQuotationController;
use App\Http\Controllers\KMJ\Insuarer\InsuarerDashboardController;

Route::prefix('insuarer')->name('insuarer.')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware('auth:insuarer')->group(function () {
        Route::get('dashboard', [InsuarerDashboardController::class, 'index'])->name('dashboard');
        Route::get('support',[InsuarerDashboardController::class, 'support'])->name('support');
    });

    Route::get('quotations', [InsuarerQuotationController::class, 'index'])->name('quotations');
    // Insurer quotation routes
    Route::get('/insuarer/quotations',       [InsuarerQuotationController::class, 'index'])->name('quotation.index');
    Route::get('/insuarer/quotations/{id}',  [InsuarerQuotationController::class, 'show'])->name('quotation.show');

    //Routes za insuarer kuview na kuset auto approve za sum insured
    Route::get('/insurer/agreements', [InsuarerQuotationController::class, 'editAgreement'])->name('agreements.show');
    Route::post('/insurer/agreements', [InsuarerQuotationController::class, 'update'])->name('settings.update');


    // Approve / Reject actions (NEW - required routes)
    Route::post('/quotations/{id}/approve',  [InsuarerQuotationController::class, 'updateStatus'])->name('quotation.updateStatusApprove');
    Route::post('/quotations/{id}/reject',   [InsuarerQuotationController::class, 'updateStatus'])->name('quotation.updateStatusReject');
    // Cover Notes


});

