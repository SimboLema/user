<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('addon_products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 300);
            $table->string('description', 800);
            $table->double('amount', 32, 2);
            $table->string('amount_type', 60);
            $table->double('rate', 12, 5);
            $table->json('applicable_to');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addon_products');
    }
};
