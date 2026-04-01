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
        Schema::create('qr_codes', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('clinic_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('doctor_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('service_point_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('target_type', ['doctor', 'room', 'department', 'branch', 'service_type', 'generic']);
            $table->unsignedBigInteger('target_id')->nullable();
            $table->string('code', 64)->unique();
            $table->string('token', 64)->unique();
            $table->string('short_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('meta')->nullable();
            $table->unsignedInteger('scan_count')->default(0);
            $table->timestamp('printed_at')->nullable();
            $table->timestamp('last_scanned_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['clinic_id', 'target_type', 'target_id']);
            $table->index(['clinic_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qr_codes');
    }
};
