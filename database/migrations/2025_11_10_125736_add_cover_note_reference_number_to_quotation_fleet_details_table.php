<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quotation_fleet_details', function (Blueprint $table) {
            $table->string('cover_note_reference_number')->nullable()->after('cover_note_number');
        });
    }

    public function down(): void
    {
        Schema::table('quotation_fleet_details', function (Blueprint $table) {
            $table->dropColumn('cover_note_reference_number');
        });
    }
};;
