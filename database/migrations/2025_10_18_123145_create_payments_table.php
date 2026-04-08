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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_id')->constrained('quotations');
            $table->foreignId('quotation_endorsement_id')->nullable()->constrained('quotation_endorsements');
            $table->foreignId('payment_mode_id')->constrained('payment_modes');

            $table->decimal('amount', 36, 2)->default(0); // premium_amount
            $table->dateTime('payment_date')->nullable();

            // Cheque fields
            $table->string('cheque_number')->nullable();
            $table->string('cheque_bank_name')->nullable();
            $table->date('cheque_date')->nullable();

            // EFT / Mobile fields
            $table->string('reference_no')->unique();
            $table->string('eft_payment_phone_no')->nullable(); // phone or account number

            $table->string('status')->default('pending');
            $table->foreignId('created_by')->constrained('users');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
