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
        Schema::create('survey_responses', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('clinic_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('doctor_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('service_point_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('qr_code_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('survey_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('channel', ['qr', 'shortlink', 'kiosk', 'telegram', 'sms'])->default('qr');
            $table->timestamp('submitted_at');
            $table->string('language', 12)->default('uz_latn');
            $table->char('ip_hash', 64)->nullable();
            $table->char('device_hash', 64)->nullable();
            $table->char('fingerprint_hash', 64)->nullable();
            $table->string('verified_token', 128)->nullable();
            $table->decimal('fraud_score', 5, 2)->default(0);
            $table->decimal('anomaly_score', 5, 2)->default(0);
            $table->decimal('sentiment_score', 5, 2)->nullable();
            $table->decimal('severity_score', 5, 2)->nullable();
            $table->decimal('confidence_score', 5, 2)->default(0);
            $table->decimal('quality_score', 5, 2)->nullable();
            $table->boolean('is_flagged')->default(false);
            $table->enum('moderation_status', ['pending', 'approved', 'rejected', 'needs_review'])->default('pending');
            $table->boolean('is_duplicate')->default(false);
            $table->foreignId('duplicate_of_response_id')->nullable()->constrained('survey_responses')->nullOnDelete();
            $table->boolean('callback_requested')->default(false);
            $table->text('callback_contact')->nullable();
            $table->text('callback_note')->nullable();
            $table->string('submitted_from_country', 64)->nullable();
            $table->timestamp('ai_processed_at')->nullable();
            $table->timestamps();

            $table->index(['clinic_id', 'submitted_at']);
            $table->index(['clinic_id', 'channel']);
            $table->index(['clinic_id', 'is_flagged']);
            $table->index(['ip_hash', 'device_hash']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_responses');
    }
};
