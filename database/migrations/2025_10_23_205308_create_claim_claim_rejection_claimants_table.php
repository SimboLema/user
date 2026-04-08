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
        if (!Schema::hasTable('claim_rejection_claimants')) {
            Schema::create('claim_rejection_claimants', function (Blueprint $table) {
                $table->id();
                $table->foreignId('claim_rejection_id')->constrained('claim_rejections');
                $table->integer('claimant_category')->nullable();
                $table->integer('claimant_type')->nullable();
                $table->string('claimant_id_number')->nullable();
                $table->integer('claimant_id_type')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('claim_rejection_claimants')) {
            Schema::dropIfExists('claim_claim_rejection_claimants');
        }
    }
};
