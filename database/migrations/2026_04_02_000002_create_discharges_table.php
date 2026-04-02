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
        Schema::create('discharges', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('clinic_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('attending_doctor_id')->nullable()->constrained('doctors')->nullOnDelete();
            $table->string('diagnosis_code')->nullable();
            $table->text('diagnosis_text')->nullable();
            $table->enum('severity_level', ['mild', 'moderate', 'severe', 'critical'])->default('moderate');
            $table->enum('discharge_type', ['recovery', 'improvement', 'transfer', 'against_advice', 'death'])->default('improvement');
            $table->boolean('requires_patronage')->default(false);
            $table->json('recommended_visit_days')->nullable();
            $table->timestamp('discharged_at');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['clinic_id', 'patient_id']);
            $table->index(['clinic_id', 'requires_patronage']);
            $table->index(['discharged_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discharges');
    }
};
