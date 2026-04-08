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
        Schema::create('claim_intimations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('claim_notification_id')->constrained('claim_notifications');
            $table->string('claim_intimation_number')->unique();
            $table->dateTime('claim_intimation_date')->nullable();
            $table->foreignId('currency_id')->nullable()->constrained('currencies');
            $table->decimal('exchange_rate', 36, 2)->nullable();
            $table->decimal('claim_estimated_amount', 36, 2)->nullable();
            $table->decimal('claim_reserve_amount', 36, 2)->nullable();
            $table->string('claim_reserve_method')->nullable();
            $table->tinyInteger('loss_assessment_option')->nullable();
            $table->string('assessor_name')->nullable();
            $table->string('assessor_id_number')->nullable();
            $table->tinyInteger('assessor_id_type')->nullable();

            // API Response & Status Fields
            $table->string('status')->default('pending');
            $table->string('acknowledgement_id')->nullable();
            $table->string('request_id')->nullable();
            $table->string('acknowledgement_status_code')->nullable();
            $table->string('acknowledgement_status_desc')->nullable();
            $table->string('response_id')->nullable();
            $table->string('response_status_code')->nullable();
            $table->string('response_status_desc')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('claim_intimations');
    }
};
