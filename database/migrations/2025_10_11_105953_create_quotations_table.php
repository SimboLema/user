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
        Schema::create('quotations', function (Blueprint $table) {
             $table->id();

            // Foreign keys
            $table->foreignId('coverage_id')->constrained('coverages');
            $table->foreignId('customer_id')->constrained('customers');
            $table->foreignId('cover_note_type_id')->nullable()->constrained('cover_note_types');
            $table->foreignId('cover_note_duration_id')->nullable()->constrained('cover_note_durations');
            $table->foreignId('payment_mode_id')->nullable()->constrained('payment_modes');
            $table->foreignId('currency_id')->nullable()->constrained('currencies');

             // Auth user
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');

            // Cover note info
            $table->string('sale_point_code')->nullable();
            $table->text('cover_note_desc')->nullable();
            $table->text('operative_clause')->nullable();
            $table->dateTime('cover_note_start_date')->nullable();
            $table->dateTime('cover_note_end_date')->nullable();
            $table->decimal('exchange_rate', 36, 5)->nullable();
            $table->decimal('total_premium_excluding_tax', 36, 2)->nullable();
            $table->decimal('total_premium_including_tax', 36, 2)->nullable();
            $table->decimal('commission_paid', 36, 2)->nullable();
            $table->decimal('commission_rate', 36, 2)->nullable();
//            $table->string('officer_name')->nullable();
//            $table->string('officer_title')->nullable();
            $table->decimal('sum_insured', 36, 2)->nullable();
            $table->decimal('premium_rate', 36, 5)->nullable();
            $table->decimal('premium_before_discount', 36, 2)->nullable();
            $table->decimal('premium_after_discount', 36, 2)->nullable();
            $table->decimal('premium_excluding_tax_equivalent', 36, 2)->nullable();
            $table->decimal('premium_including_tax', 36, 2)->nullable();
            $table->string('tax_code')->nullable();
            $table->string('is_tax_exempted')->nullable();
            $table->decimal('tax_rate', 36, 2)->nullable();
            $table->decimal('tax_amount', 36, 2)->nullable();

            // EndorsementType
            $table->foreignId('endorsement_type_id')->nullable()->constrained('endorsement_types');
            $table->string('endorsement_reason')->nullable();
            $table->decimal('endorsement_premium_earned', 36, 2)->nullable();

            // 🚗 Motors Data
            $table->foreignId('motor_category_id')->nullable()->constrained('motor_categories');
            $table->foreignId('motor_type_id')->nullable()->constrained('motor_types');
            $table->string('registration_number')->nullable();
            $table->string('chassis_number')->nullable();
            $table->string('make')->nullable();
            $table->string('model')->nullable();
            $table->string('model_number')->nullable();
            $table->string('body_type')->nullable();
            $table->string('color')->nullable();
            $table->string('engine_number')->nullable();
            $table->string('engine_capacity')->nullable();
            $table->string('fuel_used')->nullable();
            $table->integer('number_of_axles')->nullable();
            $table->string('axle_distance')->nullable();
            $table->integer('sitting_capacity')->nullable();
            $table->integer('year_of_manufacture')->nullable();
            $table->integer('tare_weight')->nullable();
            $table->integer('gross_weight')->nullable();
            $table->foreignId('motor_usage_id')->nullable()->constrained('motor_usages');
            $table->string('owner_name')->nullable();
            $table->foreignId('owner_category_id')->nullable()->constrained('owner_categories');
            $table->string('owner_address')->nullable();

            // 🧾 TIRA Response
            $table->string('acknowledgement_id')->nullable();
            $table->string('request_id')->nullable();
            $table->string('acknowledgement_status_code')->nullable();
            $table->string('acknowledgement_status_desc')->nullable();

            // Subject matter
            $table->string('subject_matter_reference', 50)->nullable();
            $table->text('subject_matter_description')->nullable();

            // form tira callback
            $table->string('response_id')->nullable();
            $table->string('prev_cover_note_reference_number')->nullable();
            $table->string('cover_note_reference')->nullable();
            $table->string('sticker_number')->nullable();
            $table->string('response_status_code')->nullable();
            $table->string('response_status_desc')->nullable();

            // $table->string('uploads')->nullable();


            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'approved', 'success', 'cancelled'])->default('pending');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
