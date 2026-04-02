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
        Schema::create('patronage_tasks', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('clinic_id')->constrained()->cascadeOnDelete();
            $table->foreignId('hospital_clinic_id')->nullable()->constrained('clinics')->nullOnDelete();
            $table->foreignId('discharge_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('family_doctor_id')->nullable()->constrained('doctors')->nullOnDelete();
            $table->enum('task_type', ['initial_visit', 'follow_up', 'emergency_check'])->default('initial_visit');
            $table->enum('priority', ['normal', 'high', 'urgent'])->default('normal');
            $table->enum('status', ['pending', 'notified', 'accepted', 'in_progress', 'completed', 'missed', 'escalated'])->default('pending');
            $table->timestamp('due_at');
            $table->timestamp('notified_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('visited_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->text('visit_notes')->nullable();
            $table->text('visit_outcome')->nullable();
            $table->unsignedTinyInteger('patient_condition_score')->nullable();
            $table->decimal('gps_latitude', 10, 7)->nullable();
            $table->decimal('gps_longitude', 10, 7)->nullable();
            $table->string('photo_proof_path')->nullable();
            $table->unsignedTinyInteger('escalation_level')->default(0);
            $table->boolean('sla_breached')->default(false);
            $table->unsignedInteger('sla_breach_minutes')->default(0);
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['clinic_id', 'family_doctor_id', 'status']);
            $table->index(['discharge_id']);
            $table->index(['patient_id']);
            $table->index(['status', 'due_at']);
            $table->index(['sla_breached']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patronage_tasks');
    }
};
