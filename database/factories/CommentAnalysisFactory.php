<?php

namespace Database\Factories;

use App\Models\Clinic;
use App\Models\CommentAnalysis;
use App\Models\SurveyResponse;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<CommentAnalysis>
 */
class CommentAnalysisFactory extends Factory
{
    public function definition(): array
    {
        return [
            'uuid' => (string) Str::uuid(),
            'clinic_id' => Clinic::factory(),
            'survey_response_id' => SurveyResponse::factory(),
            'language' => $this->faker->randomElement(['uz_latn', 'uz_cyrl', 'ru', 'en']),
            'original_comment' => $this->faker->sentence(),
            'cleaned_comment' => $this->faker->sentence(),
            'sentiment_label' => $this->faker->randomElement(['positive', 'neutral', 'negative']),
            'sentiment_score' => $this->faker->randomFloat(2, -1, 1),
            'toxicity_score' => $this->faker->randomFloat(2, 0, 1),
            'topics' => ['communication', 'waiting_time'],
            'keywords' => ['doctor', 'service'],
            'summary' => $this->faker->sentence(),
            'coaching_suggestion' => $this->faker->sentence(),
            'explained_flags' => [],
            'model_version' => 'factory',
            'processed_at' => now(),
        ];
    }
}

