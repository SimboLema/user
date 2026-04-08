<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('quotation_endorsements', function (Blueprint $table) {
            // 1. Add column after quotation_id
            $table->unsignedBigInteger('quotation_fleet_detail_id')->nullable()->after('quotation_id');

            // 2. Add foreign key separately
            $table->foreign('quotation_fleet_detail_id')->references('id')->on('quotation_fleet_details');
        });
    }

    public function down()
    {
        Schema::table('quotation_endorsements', function (Blueprint $table) {
            $table->dropForeign(['quotation_fleet_detail_id']);
            $table->dropColumn('quotation_fleet_detail_id');
        });
    }
};;
