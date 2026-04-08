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
        Schema::create('coverages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('risk_name');
            $table->string('risk_code');
            $table->decimal('rate', 10, 5);
            $table->decimal('minimum_amount', 36, 2);
            $table->unsignedBigInteger('tpp')->nullable();
            $table->unsignedBigInteger('additional_amount')->nullable();
            $table->unsignedBigInteger('per_seat')->nullable();
            $table->longText('parameters')->nullable();
            $table->string('coverage_type')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coverages');
    }
};
