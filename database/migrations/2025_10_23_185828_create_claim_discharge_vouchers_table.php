<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('claim_discharge_vouchers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('claim_notification_id')->constrained('claim_notifications');
            $table->string('discharge_voucher_number')->nullable();
            $table->string('claim_assessment_number')->nullable();
            // $table->string('cover_note_reference_number')->nullable();
            $table->dateTime('discharge_voucher_date')->nullable();
            $table->foreignId('currency_id')->constrained('currencies');
            $table->decimal('exchange_rate', 36, 2)->nullable();
            $table->dateTime('claim_offer_communication_date')->nullable();
            $table->decimal('claim_offer_amount', 36, 2)->nullable();
            $table->dateTime('claimant_response_date')->nullable();
            $table->dateTime('adjustment_date')->nullable();
            $table->text('adjustment_reason')->nullable();
            $table->decimal('adjustment_amount', 36, 2)->nullable();
            $table->dateTime('reconciliation_date')->nullable();
            $table->text('reconciliation_summary')->nullable();
            $table->decimal('reconciled_amount', 36, 2)->nullable();
            $table->enum('offer_accepted', ['Y', 'N'])->default('N');

            // Response fields
            $table->string('status')->default('pending');
            $table->string('acknowledgement_id')->nullable();
            $table->string('request_id')->nullable();
            $table->string('acknowledgement_status_code')->nullable();
            $table->string('acknowledgement_status_desc')->nullable();
            $table->string('response_id')->nullable();
            $table->string('response_status_code')->nullable();
            $table->string('response_status_desc')->nullable();

            $table->softDeletes();
            $table->timestamps();

            // Foreign keys
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('claim_discharge_vouchers');
    }
};
