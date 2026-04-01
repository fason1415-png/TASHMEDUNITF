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
        Schema::create('survey_questions', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('clinic_id')->constrained()->cascadeOnDelete();
            $table->foreignId('survey_id')->constrained()->cascadeOnDelete();
            $table->string('key');
            $table->enum('type', ['rating', 'yes_no', 'single_choice', 'comment', 'severity', 'nps', 'recommend']);
            $table->json('title');
            $table->json('description')->nullable();
            $table->unsignedInteger('order_index')->default(0);
            $table->boolean('is_required')->default(true);
            $table->decimal('weight', 5, 2)->default(1);
            $table->json('validation_rules')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['survey_id', 'order_index']);
            $table->unique(['survey_id', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_questions');
    }
};
