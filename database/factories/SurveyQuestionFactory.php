<?php

namespace Database\Factories;

use App\Models\Clinic;
use App\Models\Survey;
use App\Models\SurveyQuestion;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<SurveyQuestion>
 */
class SurveyQuestionFactory extends Factory
{
    public function definition(): array
    {
        $key = Str::snake($this->faker->unique()->words(2, true));

        return [
            'uuid' => (string) Str::uuid(),
            'clinic_id' => Clinic::factory(),
            'survey_id' => Survey::factory(),
            'key' => $key,
            'type' => $this->faker->randomElement(['rating', 'yes_no', 'single_choice', 'comment', 'severity', 'nps', 'recommend']),
            'title' => [
                'uz_latn' => ucfirst(str_replace('_', ' ', $key)).' savol',
                'uz_cyrl' => ucfirst(str_replace('_', ' ', $key)).' савол',
                'ru' => ucfirst(str_replace('_', ' ', $key)).' вопрос',
                'en' => ucfirst(str_replace('_', ' ', $key)).' question',
            ],
            'description' => null,
            'order_index' => $this->faker->numberBetween(1, 20),
            'is_required' => true,
            'weight' => 1,
            'validation_rules' => null,
            'meta' => null,
        ];
    }
}

