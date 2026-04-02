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
        Schema::create('patients', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('clinic_id')->constrained()->cascadeOnDelete();
            $table->string('pinfl');
            $table->string('full_name');
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('phone')->nullable();
            $table->string('address_region')->nullable();
            $table->string('address_district')->nullable();
            $table->text('address_text')->nullable();
            $table->foreignId('territorial_clinic_id')->nullable()->constrained('clinics')->nullOnDelete();
            $table->foreignId('family_doctor_id')->nullable()->constrained('doctors')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['clinic_id', 'family_doctor_id']);
            $table->index(['territorial_clinic_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
