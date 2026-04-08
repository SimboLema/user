<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('addons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_id')->constrained('quotations');
            $table->foreignId('addon_product_id')->constrained('addon_products');
            $table->integer('addon_reference');
            $table->text('addon_desc');
            $table->double('addon_amount', 36, 2);
            $table->double('addon_rate', 12, 5);
            $table->double('premium_excluding_tax', 36, 2);
            $table->double('premium_excluding_tax_equivalent', 36, 2);
            $table->double('premium_including_tax', 36, 2);
            $table->string('tax_code', 100);
            $table->string('is_tax_exempted', 1)->default('N');
            $table->integer('tax_exemption_type')->nullable();
            $table->string('tax_exemption_reference', 300)->nullable();
            $table->double('tax_rate', 12, 5);
            $table->double('tax_amount', 36, 2);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addons');
    }
};
