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
        Schema::create('qr_scan_events', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('clinic_id')->constrained()->cascadeOnDelete();
            $table->foreignId('qr_code_id')->constrained()->cascadeOnDelete();
            $table->enum('channel', ['qr', 'shortlink', 'kiosk', 'telegram', 'sms'])->default('qr');
            $table->char('ip_hash', 64)->nullable();
            $table->char('device_hash', 64)->nullable();
            $table->char('fingerprint_hash', 64)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('language', 12)->nullable();
            $table->timestamp('scanned_at');
            $table->foreignId('converted_to_response_id')->nullable()->constrained('survey_responses')->nullOnDelete();
            $table->timestamps();

            $table->index(['clinic_id', 'scanned_at']);
            $table->index(['qr_code_id', 'channel']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qr_scan_events');
    }
};
