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
        Schema::create('claim_intimation_claimants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('claim_intimation_id')->constrained('claim_intimations');
            $table->string('claimant_name')->nullable();
            $table->date('claimant_birth_date')->nullable();
            $table->tinyInteger('claimant_category')->nullable();
            $table->tinyInteger('claimant_type')->nullable();
            $table->string('claimant_id_number')->nullable();
            $table->tinyInteger('claimant_id_type')->nullable();
            $table->string('gender', 10)->nullable();
            $table->foreignId('district_id')->nullable()->constrained('districts');
            $table->string('street')->nullable();
            $table->string('claimant_phone_number')->nullable();
            $table->string('claimant_fax')->nullable();
            $table->string('postal_address')->nullable();
            $table->string('email_address')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Relationship
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('claim_intimation_claimants');
    }
};
