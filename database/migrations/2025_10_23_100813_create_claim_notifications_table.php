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
        Schema::create('claim_notifications', function (Blueprint $table) {
            $table->id();

            // Foreign key to quotations
            $table->foreignId('quotation_id')->constrained('quotations')->onDelete('cascade');

            // Claim data
            $table->string('claim_notification_number');
            $table->dateTime('claim_report_date');
            $table->string('claim_form_dully_filled', 1)->default('N'); // Y or N
            $table->dateTime('loss_date');
            $table->string('loss_nature');
            $table->string('loss_type');
            $table->string('loss_location');
            $table->string('officer_name');
            $table->string('officer_title');

            // Status fields
            $table->string('claim_notification_status')->default('pending'); // pending, success, fail
            $table->string('status')->default('pending');

            // 🧾 TIRA Response
            $table->string('acknowledgement_id')->nullable();
            $table->string('request_id')->nullable();
            $table->string('acknowledgement_status_code')->nullable();
            $table->string('acknowledgement_status_desc')->nullable();

            $table->string('response_id')->nullable();
            $table->string('claim_reference_number')->nullable();
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
        Schema::dropIfExists('claim_notifications');
    }
};
