<?php

namespace Tests\Feature;

use App\Jobs\ProcessSurveyResponseJob;
use App\Models\Branch;
use App\Models\Clinic;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\QrCode;
use App\Models\ServicePoint;
use App\Models\Survey;
use App\Models\SurveyQuestion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class PublicSurveySubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_patient_can_submit_feedback_from_qr_code(): void
    {
        Queue::fake();

        $clinic = Clinic::factory()->create();
        $branch = Branch::factory()->create(['clinic_id' => $clinic->id]);
        $department = Department::factory()->create(['clinic_id' => $clinic->id, 'branch_id' => $branch->id]);
        $doctor = Doctor::factory()->create([
            'clinic_id' => $clinic->id,
            'branch_id' => $branch->id,
            'department_id' => $department->id,
        ]);
        $servicePoint = ServicePoint::factory()->create([
            'clinic_id' => $clinic->id,
            'branch_id' => $branch->id,
            'department_id' => $department->id,
        ]);

        $survey = Survey::factory()->create([
            'clinic_id' => $clinic->id,
            'is_default' => true,
            'is_active' => true,
        ]);

        SurveyQuestion::factory()->create([
            'clinic_id' => $clinic->id,
            'survey_id' => $survey->id,
            'key' => 'service_quality',
            'type' => 'rating',
            'order_index' => 1,
        ]);
        SurveyQuestion::factory()->create([
            'clinic_id' => $clinic->id,
            'survey_id' => $survey->id,
            'key' => 'communication',
            'type' => 'rating',
            'order_index' => 2,
        ]);
        SurveyQuestion::factory()->create([
            'clinic_id' => $clinic->id,
            'survey_id' => $survey->id,
            'key' => 'comment',
            'type' => 'comment',
            'order_index' => 3,
            'is_required' => false,
        ]);

        $qrCode = QrCode::factory()->create([
            'clinic_id' => $clinic->id,
            'branch_id' => $branch->id,
            'department_id' => $department->id,
            'doctor_id' => $doctor->id,
            'service_point_id' => $servicePoint->id,
            'target_type' => 'doctor',
            'target_id' => $doctor->id,
            'token' => 'testtoken1234567890',
            'is_active' => true,
        ]);

        $response = $this->post(route('survey.submit', ['token' => $qrCode->token]), [
            'language' => 'uz_latn',
            'channel' => 'qr',
            'answers' => [
                'service_quality' => 5,
                'communication' => 4,
                'comment' => 'Great doctor and fast service.',
            ],
        ]);

        $response->assertRedirect(route('survey.thank-you', ['token' => $qrCode->token, 'lang' => app()->getLocale()]));

        $this->assertDatabaseHas('survey_responses', [
            'clinic_id' => $clinic->id,
            'doctor_id' => $doctor->id,
            'channel' => 'qr',
        ]);

        Queue::assertPushed(ProcessSurveyResponseJob::class);
    }
}

