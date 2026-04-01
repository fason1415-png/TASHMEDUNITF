<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Clinic;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\SurveyResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiWidgetTest extends TestCase
{
    use RefreshDatabase;

    public function test_doctor_widget_hides_score_below_minimum_sample_threshold(): void
    {
        $clinic = Clinic::factory()->create(['min_public_samples' => 10]);
        $branch = Branch::factory()->create(['clinic_id' => $clinic->id]);
        $department = Department::factory()->create(['clinic_id' => $clinic->id, 'branch_id' => $branch->id]);
        $doctor = Doctor::factory()->create([
            'clinic_id' => $clinic->id,
            'branch_id' => $branch->id,
            'department_id' => $department->id,
        ]);

        SurveyResponse::factory()->count(5)->create([
            'clinic_id' => $clinic->id,
            'branch_id' => $branch->id,
            'department_id' => $department->id,
            'doctor_id' => $doctor->id,
            'moderation_status' => 'approved',
            'quality_score' => 80,
            'confidence_score' => 75,
        ]);

        $response = $this->getJson(route('api.widgets.doctor', ['doctor' => $doctor->id]));
        $response->assertOk();
        $response->assertJsonPath('score_visible', false);
        $response->assertJsonPath('quality_score', null);

        SurveyResponse::factory()->count(6)->create([
            'clinic_id' => $clinic->id,
            'branch_id' => $branch->id,
            'department_id' => $department->id,
            'doctor_id' => $doctor->id,
            'moderation_status' => 'approved',
            'quality_score' => 90,
            'confidence_score' => 88,
        ]);

        $response = $this->getJson(route('api.widgets.doctor', ['doctor' => $doctor->id]));
        $response->assertOk();
        $response->assertJsonPath('score_visible', true);
        $this->assertNotNull($response->json('quality_score'));
    }
}

