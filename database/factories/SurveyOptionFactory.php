<?php

namespace Database\Factories;

use App\Models\Clinic;
use App\Models\SurveyOption;
use App\Models\SurveyQuestion;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<SurveyOption>
 */
class SurveyOptionFactory extends Factory
{
    public function definition(): array
    {
        $value = Str::lower($this->faker->lexify('option_????'));

        return [
            'uuid' => (string) Str::uuid(),
            'clinic_id' => Clinic::factory(),
            'survey_question_id' => SurveyQuestion::factory(),
            'label' => [
                'uz_latn' => ucfirst(str_replace('_', ' ', $value)),
                'uz_cyrl' => ucfirst(str_replace('_', ' ', $value)),
                'ru' => ucfirst(str_replace('_', ' ', $value)),
                'en' => ucfirst(str_replace('_', ' ', $value)),
            ],
            'value' => $value,
            'order_index' => $this->faker->numberBetween(1, 8),
            'score_value' => $this->faker->randomFloat(2, 0, 100),
            'is_active' => true,
        ];
    }
}

