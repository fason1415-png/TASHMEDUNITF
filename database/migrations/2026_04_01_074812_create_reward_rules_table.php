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
        Schema::create('reward_rules', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('clinic_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('trigger_type', ['score_threshold', 'rank', 'low_complaint', 'high_nps', 'custom']);
            $table->json('conditions')->nullable();
            $table->enum('reward_type', ['bonus', 'badge', 'certificate', 'days_off', 'custom']);
            $table->decimal('reward_value', 12, 2)->nullable();
            $table->json('reward_meta')->nullable();
            $table->enum('period_type', ['monthly', 'quarterly', 'yearly'])->default('monthly');
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['clinic_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reward_rules');
    }
};
