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
        Schema::create('rating_snapshots', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('clinic_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('doctor_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('period_type', ['realtime', 'daily', 'weekly', 'monthly']);
            $table->date('period_start');
            $table->date('period_end');
            $table->unsignedInteger('feedback_count')->default(0);
            $table->unsignedInteger('flagged_count')->default(0);
            $table->decimal('quality_score', 5, 2)->nullable();
            $table->decimal('confidence_adjusted_score', 5, 2)->nullable();
            $table->decimal('sentiment_score', 5, 2)->nullable();
            $table->decimal('service_quality_score', 5, 2)->nullable();
            $table->decimal('communication_score', 5, 2)->nullable();
            $table->decimal('wait_time_score', 5, 2)->nullable();
            $table->decimal('explanation_score', 5, 2)->nullable();
            $table->decimal('resolution_score', 5, 2)->nullable();
            $table->decimal('nps_score', 5, 2)->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['clinic_id', 'period_type', 'period_start', 'period_end'], 'rt_snapshots_period_idx');
            $table->index(['clinic_id', 'doctor_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rating_snapshots');
    }
};
