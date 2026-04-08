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
        Schema::create('sms_responses', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('numbers')->nullable(); // JSON encoded phone numbers
            $table->string('successful')->nullable(); // Successful response
            $table->string('request_id')->nullable(); // Request ID
            $table->string('code')->nullable(); // Response code
            $table->string('message')->nullable(); // Response message
            $table->string('valid')->nullable(); // Valid numbers
            $table->string('invalid')->nullable(); // Invalid numbers
            $table->string('duplicates')->nullable(); // Duplicate numbers
            $table->string('created_by'); // Creator ID
            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_responses');
    }
};
