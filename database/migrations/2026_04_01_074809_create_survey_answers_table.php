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
        Schema::create('survey_answers', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('clinic_id')->constrained()->cascadeOnDelete();
            $table->foreignId('survey_response_id')->constrained()->cascadeOnDelete();
            $table->foreignId('survey_question_id')->constrained()->cascadeOnDelete();
            $table->string('question_type');
            $table->unsignedTinyInteger('rating_value')->nullable();
            $table->boolean('boolean_value')->nullable();
            $table->string('option_value')->nullable();
            $table->unsignedTinyInteger('nps_value')->nullable();
            $table->unsignedTinyInteger('severity_level')->nullable();
            $table->text('text_answer')->nullable();
            $table->decimal('normalized_score', 5, 2)->nullable();
            $table->timestamps();

            $table->index(['survey_response_id', 'survey_question_id']);
            $table->index(['clinic_id', 'question_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_answers');
    }
};
