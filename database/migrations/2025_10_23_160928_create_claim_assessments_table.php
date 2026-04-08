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
        Schema::create('claim_assessments', function (Blueprint $table) {
            $table->id();
            $table->string('claim_assessment_number')->unique();
            $table->string('claim_intimation_number');
            $table->foreignId('claim_notification_id')->constrained('claim_notifications');
            $table->dateTime('assessment_received_date')->nullable();
            $table->text('assessment_report_summary')->nullable();
            $table->foreignId('currency_id')->nullable()->constrained('currencies');
            $table->decimal('exchange_rate', 36, 2)->nullable();
            $table->decimal('assessment_amount', 36, 2)->nullable();
            $table->decimal('approved_claim_amount', 36, 2)->nullable();
            $table->dateTime('claim_approval_date')->nullable();
            $table->string('claim_approval_authority')->nullable();
            $table->enum('is_re_assessment', ['Y', 'N'])->default('N');
            $table->string('status')->default('pending');
            $table->string('acknowledgement_id')->nullable();
            $table->string('request_id')->nullable();
            $table->string('acknowledgement_status_code')->nullable();
            $table->text('acknowledgement_status_desc')->nullable();
            $table->string('response_id')->nullable();
            $table->string('response_status_code')->nullable();
            $table->text('response_status_desc')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('claim_assessments');
    }
};
