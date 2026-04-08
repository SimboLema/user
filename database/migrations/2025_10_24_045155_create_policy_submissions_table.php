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
        Schema::create('policy_submissions', function (Blueprint $table) {
            $table->id();

            // Basic Policy Info
            $table->string('policy_number')->nullable();
            $table->text('policy_operative_clause')->nullable();
            $table->text('special_conditions')->nullable();
            $table->text('exclusions')->nullable();

            // JSON field for multiple cover note references
            $table->json('cover_note_reference_numbers')->nullable();

            // Status & TIRA Fields
            $table->string('status')->default('pending');
            $table->string('acknowledgement_id')->nullable();
            $table->string('request_id')->nullable();
            $table->string('acknowledgement_status_code')->nullable();
            $table->string('acknowledgement_status_desc')->nullable();
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
        Schema::dropIfExists('policy_submissions');
    }
};
