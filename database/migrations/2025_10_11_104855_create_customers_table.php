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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // policy_holder_name
            $table->date('dob')->nullable(); // policy_holder_birth_date
            $table->foreignId('policy_holder_type_id')->nullable()->constrained('policy_holder_types');
            $table->string('policy_holder_id_number')->nullable();
            $table->foreignId('policy_holder_id_type_id')->nullable()->constrained('policy_holder_id_types');
            $table->string('gender')->nullable();
            $table->foreignId('district_id')->nullable()->constrained('districts');
            $table->string('street')->nullable();
            $table->string('phone')->nullable(); // policy_holder_phone_number
            $table->string('fax')->nullable(); // policy_holder_fax
            $table->text('postal_address')->nullable();
            $table->string('email_address')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
