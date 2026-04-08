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
        Schema::table('quotation_fleet_details', function (Blueprint $table) {
            $table->json('addons')
                ->nullable()
                ->after('quotation_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotation_fleet_details', function (Blueprint $table) {
            $table->dropColumn('addons');
        });
    }
};
