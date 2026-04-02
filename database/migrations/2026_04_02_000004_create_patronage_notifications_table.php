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
        Schema::create('patronage_notifications', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('patronage_task_id')->constrained()->cascadeOnDelete();
            $table->enum('channel', ['sms', 'push', 'telegram', 'email']);
            $table->enum('recipient_type', ['doctor', 'supervisor', 'chief_doctor', 'ministry']);
            $table->foreignId('recipient_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('message_body');
            $table->enum('status', ['pending', 'sent', 'delivered', 'failed', 'read'])->default('pending');
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->unsignedTinyInteger('attempt_count')->default(0);
            $table->text('error_message')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['patronage_task_id', 'channel']);
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patronage_notifications');
    }
};
