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
        Schema::create('patronage_escalation_rules', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('clinic_id')->nullable()->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('escalation_level');
            $table->unsignedInteger('trigger_after_minutes');
            $table->string('notify_role');
            $table->json('notification_channels');
            $table->boolean('auto_reassign')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['clinic_id', 'escalation_level']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patronage_escalation_rules');
    }
};
