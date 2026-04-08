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
        Schema::create('claim_assessment_claimants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('claim_assessment_id')->constrained('claim_assessments');
            $table->tinyInteger('claimant_category')->nullable(); // 1-Policyholder, 2-Third Party
            $table->tinyInteger('claimant_type')->nullable(); // 1-Individual, 2-Corporate
            $table->string('claimant_id_number')->nullable();
            $table->tinyInteger('claimant_id_type')->nullable(); // 1-NIN, 2-Voter ID etc.
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('claim_assessment_claimants');
    }
};
