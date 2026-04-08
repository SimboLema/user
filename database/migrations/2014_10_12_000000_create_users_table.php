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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->unique();
            $table->string('title')->nullable();
            $table->string('password');
            $table->timestamp('phone_verified_at')->nullable();
            $table->string('is_account_verified')->default(1);
            $table->string('gender')->nullable();
            $table->string('token')->nullable();
            $table->timestamp('token_expired')->nullable();
            $table->string('status')->default('active');
            $table->string('role')->default(2);
            $table->string('avatar')->nullable();
            $table->timestamp('last_login')->nullable();
            $table->string('country_id')->nullable();
            $table->string('region_id')->nullable();
            $table->string('district_id')->nullable();
            $table->string('ward_id')->nullable();
            $table->string('address')->nullable();
            $table->string('home_address')->nullable();
            $table->string('created_by')->nullable();

            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
