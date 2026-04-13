<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('whatsapp_messages', function (Blueprint $table) {
            $table->id();
            $table->string('message_id')->unique();
            $table->string('from_phone');
            $table->string('to_phone');
            $table->string('type')->default('text');
            $table->longText('content');
            $table->enum('direction', ['inbound', 'outbound'])->default('inbound');
            $table->enum('status', ['pending', 'sent', 'delivered', 'read', 'failed'])->nullable();
            $table->timestamp('timestamp')->useCurrent();
            $table->timestamp('status_updated_at')->nullable();
            $table->json('errors')->nullable();
            $table->longText('raw_data')->nullable();
            $table->timestamps();

            $table->index('from_phone');
            $table->index('to_phone');
            $table->index('direction');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_messages');
    }
};