<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('claim_rejections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('claim_notification_id')->constrained('claim_notifications');
            $table->string('claim_rejection_number')->unique();
            $table->string('claim_intimation_number');
            $table->dateTime('rejection_date')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->string('claim_resulted_litigation', 1)->nullable();
            $table->decimal('claim_amount', 36, 2)->nullable();
            $table->foreignId('currency_id')->constrained('currencies');
            $table->decimal('exchange_rate', 36, 2)->nullable();

            // Status & TIRA Fields
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
        Schema::dropIfExists('claim_rejections');
    }
};
