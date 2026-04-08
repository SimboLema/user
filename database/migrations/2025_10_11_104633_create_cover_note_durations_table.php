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
        Schema::create('cover_note_durations', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('months'); //(1, 3, 6, 12, etc.)
            $table->string('label')->nullable(); // "1 Month", "6 Months", "1 Year"
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cover_note_durations');
    }
};
