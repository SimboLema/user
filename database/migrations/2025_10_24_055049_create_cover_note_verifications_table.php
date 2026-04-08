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
        Schema::create('cover_note_verifications', function (Blueprint $table) {
            $table->id();
            $table->string('cover_note_reference_number');
            $table->string('sticker_number');
            $table->string('motor_registration_number');
            $table->string('motor_chassis_number');

            // Status & TIRA fields
            $table->string('status')->default('pending');
            // TIRA Response fields
            $table->string('response_id')->nullable();
            $table->string('request_id')->nullable();
            $table->string('response_status_code')->nullable();
            $table->string('response_status_desc')->nullable();

            // Store full TIRA XML/JSON as one column
            $table->json('tira_response')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cover_note_verifications');
    }
};
