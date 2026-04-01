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
        Schema::create('comment_analysis', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('clinic_id')->constrained()->cascadeOnDelete();
            $table->foreignId('survey_response_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('language', 12)->nullable();
            $table->text('original_comment')->nullable();
            $table->text('cleaned_comment')->nullable();
            $table->enum('sentiment_label', ['positive', 'neutral', 'negative'])->nullable();
            $table->decimal('sentiment_score', 5, 2)->nullable();
            $table->decimal('toxicity_score', 5, 2)->nullable();
            $table->json('topics')->nullable();
            $table->json('keywords')->nullable();
            $table->text('summary')->nullable();
            $table->text('coaching_suggestion')->nullable();
            $table->json('explained_flags')->nullable();
            $table->string('model_version')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->index(['clinic_id', 'sentiment_label']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comment_analysis');
    }
};
