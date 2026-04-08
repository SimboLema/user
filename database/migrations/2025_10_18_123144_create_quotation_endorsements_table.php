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
        Schema::create('quotation_endorsements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_id')->constrained('quotations')->onDelete('cascade');
            $table->foreignId('endorsement_type_id')->constrained('endorsement_types')->onDelete('cascade');
            $table->string('previous_covernote_reference_number')->nullable();
            $table->text('description')->nullable();
            $table->decimal('old_premium', 36, 2)->nullable();
            $table->decimal('new_premium', 36, 2)->nullable();
            $table->decimal('endorsement_premium_earned', 36, 2)->nullable();
            $table->date('cancellation_date')->nullable();
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
        Schema::dropIfExists('quotation_endorsements');
    }
};
