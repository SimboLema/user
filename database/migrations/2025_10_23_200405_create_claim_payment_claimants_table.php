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
        Schema::create('claim_payment_claimants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('claim_payment_id')->constrained('claim_payments');
            $table->integer('claimant_category')->nullable();
            $table->integer('claimant_type')->nullable();
            $table->string('claimant_id_number')->nullable();
            $table->integer('claimant_id_type')->nullable();
            $table->timestamps();
             $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('claim_payment_claimants');
    }
};
