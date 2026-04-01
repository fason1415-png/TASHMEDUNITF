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
        Schema::create('doctors', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('clinic_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->string('full_name');
            $table->string('specialty')->nullable();
            $table->enum('status', ['active', 'on_leave', 'inactive', 'archived'])->default('active');
            $table->string('photo')->nullable();
            $table->unsignedInteger('experience_years')->default(0);
            $table->text('bio')->nullable();
            $table->enum('consultation_type', ['offline', 'online', 'hybrid'])->default('offline');
            $table->boolean('is_active')->default(true);
            $table->date('hired_at')->nullable();
            $table->date('left_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['clinic_id', 'branch_id', 'department_id']);
            $table->index(['clinic_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
