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
        Schema::create('pusher_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('message')->nullable();
            $table->string('date')->nullable();
            $table->string('time')->nullable();
            $table->unsignedInteger('is_clicked')->default(0);
            $table->unsignedInteger('is_opened')->default(0);
            $table->unsignedInteger('is_delivered')->default(0);
            $table->string('user_id')->nullable();
            $table->text('redirect_link')->nullable();

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
        Schema::dropIfExists('pusher_notifications');
    }
};
