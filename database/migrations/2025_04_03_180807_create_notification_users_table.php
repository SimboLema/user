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
        Schema::create('notification_users', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('template_id');
            $table->string('type')->nullable(); // Date t // Penalty amount
            $table->string('user_id')->nullable();
            $table->unsignedBigInteger('archive')->default(0); // Date the penalty was recorded
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_users');
    }
};
