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
        Schema::create('reinsurances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_id')->constrained('quotations');
            $table->foreignId('currency_id')->nullable()->constrained('currencies');
            $table->foreignId('reinsurance_category_id')->constrained('reinsurance_categories');
            $table->decimal('exchange_rate', 36, 2)->default(1.00);
            $table->string('authorizing_officer_name')->nullable();
            $table->string('authorizing_officer_title')->nullable();
            $table->string('status')->default('pending');

            // 🧾 TIRA Response
            $table->string('acknowledgement_id')->nullable();
            $table->string('request_id')->nullable();
            $table->string('acknowledgement_status_code')->nullable();
            $table->string('acknowledgement_status_desc')->nullable();

            // form tira callback
            $table->string('response_id')->nullable();
            $table->string('response_status_code')->nullable();
            $table->string('response_status_desc')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reinsurances');
    }
};
