<?php

namespace Database\Factories;

use App\Models\Clinic;
use App\Models\SurveyAnswer;
use App\Models\SurveyQuestion;
use App\Models\SurveyResponse;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<SurveyAnswer>
 */
class SurveyAnswerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'uuid' => (string) Str::uuid(),
            'clinic_id' => Clinic::factory(),
            'survey_response_id' => SurveyResponse::factory(),
            'survey_question_id' => SurveyQuestion::factory(),
            'question_type' => 'rating',
            'rating_value' => $this->faker->numberBetween(1, 5),
            'boolean_value' => null,
            'option_value' => null,
            'nps_value' => null,
            'severity_level' => null,
            'text_answer' => null,
            'normalized_score' => $this->faker->randomFloat(2, 0, 100),
        ];
    }
}

