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
    Schema::table('insuarers', function (Blueprint $table) {
        $table->decimal('auto_approval_limit', 20, 2)->nullable()->after('email');
    });
}

public function down(): void
{
    Schema::table('insuarers', function (Blueprint $table) {
        $table->dropColumn('auto_approval_limit');
    });
}
};
