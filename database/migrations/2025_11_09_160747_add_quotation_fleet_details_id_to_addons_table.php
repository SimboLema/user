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
        Schema::table('addons', function (Blueprint $table) {
            $table->foreignId('quotation_fleet_details_id')
                ->nullable()                    // nullable kwa sababu si zote zitakuwa na fleet detail
                ->after('quotation_id')         // inakuja baada ya quotation_id
                ->constrained('quotation_fleet_details')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('addons', function (Blueprint $table) {
            $table->dropForeign(['quotation_fleet_details_id']);
            $table->dropColumn('quotation_fleet_details_id');
        });
    }
};
