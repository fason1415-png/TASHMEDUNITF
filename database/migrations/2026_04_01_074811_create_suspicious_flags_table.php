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
        Schema::create('suspicious_flags', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('clinic_id')->constrained()->cascadeOnDelete();
            $table->foreignId('survey_response_id')->constrained()->cascadeOnDelete();
            $table->enum('flag_type', ['ip_duplicate', 'device_duplicate', 'time_burst', 'pattern', 'toxicity', 'ai_anomaly', 'manual']);
            $table->decimal('score', 5, 2)->default(0);
            $table->text('reason');
            $table->json('evidence')->nullable();
            $table->enum('status', ['open', 'confirmed', 'dismissed'])->default('open');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->index(['clinic_id', 'flag_type']);
            $table->index(['clinic_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suspicious_flags');
    }
};
