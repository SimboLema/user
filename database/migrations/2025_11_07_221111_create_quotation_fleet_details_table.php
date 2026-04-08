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
        Schema::create('quotation_fleet_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_id')->constrained('quotations')->onDelete('cascade');

            // Fleet/CoverNote fields
            $table->tinyInteger('fleet_entry')->nullable();
            $table->string('cover_note_number')->nullable();
            $table->string('prev_cover_note_reference_number')->nullable();
            $table->text('cover_note_desc')->nullable();
            $table->text('operative_clause')->nullable();
            $table->integer('endorsement_type')->nullable();
            $table->text('endorsement_reason')->nullable();
            $table->decimal('endorsement_premium_earned', 36, 2)->nullable();

            // RiskCovered fields
            $table->string('risk_code')->nullable();
            $table->decimal('sum_insured', 36, 2)->nullable();
            $table->decimal('sum_insured_equivalent', 36, 2)->nullable();
            $table->decimal('premium_rate', 8, 5)->nullable();
            $table->decimal('premium_before_discount', 36, 2)->nullable();
            $table->decimal('premium_after_discount', 36, 2)->nullable();
            $table->decimal('premium_excluding_tax_equivalent', 36, 2)->nullable();
            $table->decimal('premium_including_tax', 36, 2)->nullable();
            $table->tinyInteger('discount_type')->nullable();
            $table->decimal('discount_rate', 8, 5)->nullable();
            $table->decimal('discount_amount', 36, 2)->nullable();
            $table->string('tax_code')->nullable();
            $table->char('is_tax_exempted', 1)->nullable();
            $table->string('tax_exemption_type')->nullable();
            $table->string('tax_exemption_reference')->nullable();
            $table->decimal('tax_rate', 8, 5)->nullable();
            $table->decimal('tax_amount', 36, 2)->nullable();

            // SubjectMatter
            $table->string('subject_matter_reference')->nullable();
            $table->text('subject_matter_desc')->nullable();

            // MotorDtl fields
            $table->tinyInteger('motor_category')->nullable();
            $table->tinyInteger('motor_type')->nullable();
            $table->string('registration_number')->nullable();
            $table->string('chassis_number')->nullable();
            $table->string('make')->nullable();
            $table->string('model')->nullable();
            $table->string('model_number')->nullable();
            $table->string('body_type')->nullable();
            $table->string('color')->nullable();
            $table->string('engine_number')->nullable();
            $table->integer('engine_capacity')->nullable();
            $table->string('fuel_used')->nullable();
            $table->integer('number_of_axles')->nullable();
            $table->integer('axle_distance')->nullable();
            $table->integer('sitting_capacity')->nullable();
            $table->bigInteger('year_of_manufacture')->nullable();
            $table->bigInteger('tare_weight')->nullable();
            $table->bigInteger('gross_weight')->nullable();
            $table->tinyInteger('motor_usage')->nullable();
            $table->string('owner_name')->nullable();
            $table->tinyInteger('owner_category')->nullable();
            $table->string('owner_address')->nullable();

            // 🧾 TIRA Response
            $table->string('acknowledgement_id')->nullable();
            $table->string('request_id')->nullable();
            $table->string('acknowledgement_status_code')->nullable();
            $table->string('acknowledgement_status_desc')->nullable();

            // form tira callback
            $table->string('response_id')->nullable();
            $table->string('sticker_number')->nullable();
            $table->string('response_status_code')->nullable();
            $table->string('response_status_desc')->nullable();

            $table->string('status')->default('pending');


            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_fleet_details');
    }
};
