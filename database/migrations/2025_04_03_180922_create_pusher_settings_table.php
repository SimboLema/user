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
        Schema::create('pusher_settings', function (Blueprint $table) {
            $table->id();
            $table->string('api_id')->nullable();
            $table->string('key')->nullable();
            $table->string('secret')->nullable();
            $table->string('cluster')->nullable();
            $table->string('channel')->nullable();

            $table->unsignedInteger('created_by');
            $table->timestamps();
            $table->unsignedInteger('archive')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pusher_settings');
    }
};
