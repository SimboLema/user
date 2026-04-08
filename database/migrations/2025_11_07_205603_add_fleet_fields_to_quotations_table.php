<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            if (!Schema::hasColumn('quotations', 'fleet_id')) {
                $table->string('fleet_id')->nullable()->after('id');
            }

            if (!Schema::hasColumn('quotations', 'fleet_type')) {
                $table->tinyInteger('fleet_type')->nullable()->after('fleet_id');
            }

            if (!Schema::hasColumn('quotations', 'fleet_size')) {
                $table->integer('fleet_size')->nullable()->after('fleet_type');
            }

            if (!Schema::hasColumn('quotations', 'comprehensive_insured')) {
                $table->integer('comprehensive_insured')->nullable()->after('fleet_size');
            }

            if (!Schema::hasColumn('quotations', 'addons')) {
                $table->json('addons')->nullable()->after('comprehensive_insured');
            }
        });
    }

    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            if (Schema::hasColumn('quotations', 'fleet_id')) {
                $table->dropColumn('fleet_id');
            }
            if (Schema::hasColumn('quotations', 'fleet_type')) {
                $table->dropColumn('fleet_type');
            }
            if (Schema::hasColumn('quotations', 'fleet_size')) {
                $table->dropColumn('fleet_size');
            }
            if (Schema::hasColumn('quotations', 'comprehensive_insured')) {
                $table->dropColumn('comprehensive_insured');
            }
            if (Schema::hasColumn('quotations', 'addons')) {
                $table->dropColumn('addons');
            }
        });
    }
};;
