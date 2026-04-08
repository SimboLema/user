<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('reinsurance_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reinsurance_id')->constrained('reinsurances');
            $table->string('participant_code')->nullable();
            $table->foreignId('participant_type_id')->constrained('participant_types');
            $table->foreignId('reinsurance_form_id')->constrained('reinsurance_forms');
            $table->foreignId('reinsurance_type_id')->constrained('reinsurance_types');
            $table->string('rebroker_code')->nullable();
            $table->decimal('brokerage_commission', 36, 2)->nullable();
            $table->decimal('reinsurance_commission', 36, 2)->nullable();
            $table->decimal('premium_share', 36, 2)->nullable();
            $table->dateTime('participation_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reinsurance_participants');
    }
};
