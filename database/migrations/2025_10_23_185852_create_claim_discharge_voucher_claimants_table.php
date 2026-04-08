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
        Schema::create('claim_discharge_voucher_claimants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('discharge_voucher_id')
                ->constrained('claim_discharge_vouchers');
            $table->unsignedInteger('claimant_category')->nullable();
            $table->unsignedInteger('claimant_type')->nullable();
            $table->string('claimant_id_number')->nullable();
            $table->unsignedInteger('claimant_id_type')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('claim_discharge_voucher_claimants');
    }
};
