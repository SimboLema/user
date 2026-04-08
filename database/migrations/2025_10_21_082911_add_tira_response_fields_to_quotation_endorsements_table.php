<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('quotation_endorsements', function (Blueprint $table) {
            // 🧾 TIRA Response Fields
            $table->string('acknowledgement_id')->nullable()->before('status');
            $table->string('request_id')->nullable()->before('status');
            $table->string('acknowledgement_status_code')->nullable()->before('status');
            $table->string('acknowledgement_status_desc')->nullable()->before('status');

            // 📨 Form TIRA Callback Fields
            $table->string('response_id')->nullable()->before('status');
            $table->string('cover_note_reference')->nullable()->before('status');
            $table->string('sticker_number')->nullable()->before('status');
            $table->string('response_status_code')->nullable()->before('status');
            $table->string('response_status_desc')->nullable()->before('status');
        });
    }

    public function down(): void
    {
        Schema::table('quotation_endorsements', function (Blueprint $table) {
            $table->dropColumn([
                'acknowledgement_id',
                'request_id',
                'acknowledgement_status_code',
                'acknowledgement_status_desc',
                'response_id',
                'cover_note_reference',
                'sticker_number',
                'response_status_code',
                'response_status_desc',
            ]);
        });
    }
};
