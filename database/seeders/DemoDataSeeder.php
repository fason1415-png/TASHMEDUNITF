<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\Branch;
use App\Models\Clinic;
use App\Models\CommentAnalysis;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\DoctorProfile;
use App\Models\Escalation;
use App\Models\Invoice;
use App\Models\LanguageString;
use App\Models\QrCode;
use App\Models\QrScanEvent;
use App\Models\RatingSnapshot;
use App\Models\Reward;
use App\Models\RewardRule;
use App\Models\ServicePoint;
use App\Models\Subscription;
use App\Models\Survey;
use App\Models\SurveyAnswer;
use App\Models\SurveyQuestion;
use App\Models\SurveyResponse;
use App\Models\SuspiciousFlag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $clinicPrimary = Clinic::factory()->create([
            'name' => 'ShifoReyting Demo Clinic',
            'slug' => 'shiforeyting-demo-clinic',
            'city' => 'Tashkent',
            'subscription_plan' => 'enterprise',
            'min_public_samples' => 10,
        ]);

        $clinicSecondary = Clinic::factory()->create([
            'name' => 'Andijan Family Health',
            'slug' => 'andijan-family-health',
            'city' => 'Andijan',
            'subscription_plan' => 'standard',
            'min_public_samples' => 10,
        ]);

        $this->seedClinicData($clinicPrimary, true);
        $this->seedClinicData($clinicSecondary, false);
    }

    private function seedClinicData(Clinic $clinic, bool $isPrimary): void
    {
        $branches = Branch::factory()->count(2)->create(['clinic_id' => $clinic->id]);

        $departments = collect();
        foreach ($branches as $branch) {
            $departments = $departments->merge(
                Department::factory()->count(3)->create([
                    'clinic_id' => $clinic->id,
                    'branch_id' => $branch->id,
                ])
            );
        }

        $doctors = collect();
        foreach ($departments as $department) {
            $doctors = $doctors->merge(
                Doctor::factory()->count(2)->create([
                    'clinic_id' => $clinic->id,
                    'branch_id' => $department->branch_id,
                    'department_id' => $department->id,
                ])
            );
        }

        foreach ($doctors as $doctor) {
            DoctorProfile::factory()->create([
                'clinic_id' => $clinic->id,
                'doctor_id' => $doctor->id,
            ]);
        }

        $servicePoints = collect();
        foreach ($departments as $department) {
            $servicePoints = $servicePoints->merge(
                ServicePoint::factory()->count(2)->create([
                    'clinic_id' => $clinic->id,
                    'branch_id' => $department->branch_id,
                    'department_id' => $department->id,
                ])
            );
        }

        $survey = $this->createDefaultSurvey($clinic);
        $questions = $survey->questions()->get()->keyBy('key');

        $qrCodes = collect();
        foreach ($doctors as $doctor) {
            $servicePoint = $servicePoints->firstWhere('department_id', $doctor->department_id);
            $qrCode = QrCode::query()->firstOrCreate(
                [
                    'clinic_id' => $clinic->id,
                    'target_type' => 'doctor',
                    'target_id' => $doctor->id,
                ],
                [
                    'code' => strtoupper(Str::random(10)),
                    'token' => Str::random(40),
                    'is_active' => true,
                ],
            );

            $qrCode->update([
                'branch_id' => $doctor->branch_id,
                'department_id' => $doctor->department_id,
                'doctor_id' => $doctor->id,
                'service_point_id' => $servicePoint?->id,
                'short_url' => url('/f/'.Str::random(40)),
                'scan_count' => 0,
            ]);

            $qrCodes->push($qrCode->fresh());
        }

        $this->createClinicUsers($clinic, $branches, $doctors, $isPrimary);
        $this->createBillingData($clinic);

        $this->seedResponses($clinic, $survey, $questions, $doctors, $qrCodes);
        $this->seedRatingsAndRewards($clinic, $doctors);
        $this->seedLanguageAndAudit($clinic);
    }

    private function createDefaultSurvey(Clinic $clinic): Survey
    {
        $survey = Survey::factory()->create([
            'clinic_id' => $clinic->id,
            'name' => 'Main Patient Survey',
            'slug' => 'main-patient-survey-'.$clinic->id,
            'is_default' => true,
        ]);

        $questionPayload = [
            ['key' => 'service_quality', 'type' => 'rating', 'order' => 1, 'title' => 'Service quality'],
            ['key' => 'communication', 'type' => 'rating', 'order' => 2, 'title' => 'Doctor communication'],
            ['key' => 'waiting_experience', 'type' => 'rating', 'order' => 3, 'title' => 'Waiting experience'],
            ['key' => 'explanation_quality', 'type' => 'rating', 'order' => 4, 'title' => 'Explanation quality'],
            ['key' => 'would_recommend', 'type' => 'recommend', 'order' => 5, 'title' => 'Would you recommend?'],
            ['key' => 'complaint_severity', 'type' => 'severity', 'order' => 6, 'title' => 'Complaint severity'],
            ['key' => 'comment', 'type' => 'comment', 'order' => 7, 'title' => 'Comment'],
        ];

        foreach ($questionPayload as $item) {
            SurveyQuestion::factory()->create([
                'clinic_id' => $clinic->id,
                'survey_id' => $survey->id,
                'key' => $item['key'],
                'type' => $item['type'],
                'title' => [
                    'uz_latn' => $item['title'],
                    'uz_cyrl' => $item['title'],
                    'ru' => $item['title'],
                    'en' => $item['title'],
                ],
                'order_index' => $item['order'],
                'is_required' => $item['type'] !== 'comment',
            ]);
        }

        return $survey;
    }

    private function createClinicUsers(Clinic $clinic, Collection $branches, Collection $doctors, bool $isPrimary): void
    {
        $clinicAdmin = User::query()->create([
            'clinic_id' => $clinic->id,
            'branch_id' => $branches->first()->id,
            'name' => $isPrimary ? 'Clinic Admin Demo' : 'Clinic Admin '.$clinic->id,
            'email' => $isPrimary ? 'clinic.admin@shiforeyting.local' : 'clinic'.$clinic->id.'.admin@shiforeyting.local',
            'phone' => '+998901112233',
            'email_verified_at' => now(),
            'password' => Hash::make('Password123!'),
            'preferred_language' => 'uz_latn',
            'is_active' => true,
        ]);
        $clinicAdmin->assignRole('clinic_admin');

        $analyst = User::query()->create([
            'clinic_id' => $clinic->id,
            'branch_id' => $branches->first()->id,
            'name' => 'Clinic Analyst '.$clinic->id,
            'email' => 'analyst'.$clinic->id.'@shiforeyting.local',
            'phone' => '+998901112244',
            'email_verified_at' => now(),
            'password' => Hash::make('Password123!'),
            'preferred_language' => 'ru',
            'is_active' => true,
        ]);
        $analyst->assignRole('analyst');

        $support = User::query()->create([
            'clinic_id' => $clinic->id,
            'branch_id' => $branches->first()->id,
            'name' => 'Support Moderator '.$clinic->id,
            'email' => 'support'.$clinic->id.'@shiforeyting.local',
            'phone' => '+998901112255',
            'email_verified_at' => now(),
            'password' => Hash::make('Password123!'),
            'preferred_language' => 'en',
            'is_active' => true,
        ]);
        $support->assignRole('support_moderator');

        foreach ($branches as $branch) {
            $manager = User::query()->create([
                'clinic_id' => $clinic->id,
                'branch_id' => $branch->id,
                'name' => 'Branch Manager '.$branch->id,
                'email' => 'branch'.$branch->id.'.manager@shiforeyting.local',
                'phone' => '+9989011100'.$branch->id,
                'email_verified_at' => now(),
                'password' => Hash::make('Password123!'),
                'preferred_language' => 'uz_latn',
                'is_active' => true,
            ]);
            $manager->assignRole('branch_manager');
        }

        foreach ($doctors->take(6) as $doctor) {
            $doctorUser = User::query()->create([
                'clinic_id' => $clinic->id,
                'branch_id' => $doctor->branch_id,
                'doctor_id' => $doctor->id,
                'name' => $doctor->full_name,
                'email' => 'doctor'.$doctor->id.'@shiforeyting.local',
                'phone' => '+99890999'.$doctor->id,
                'email_verified_at' => now(),
                'password' => Hash::make('Password123!'),
                'preferred_language' => 'uz_latn',
                'is_active' => true,
            ]);
            $doctorUser->assignRole('doctor');
        }
    }

    private function createBillingData(Clinic $clinic): void
    {
        $subscription = Subscription::factory()->create([
            'clinic_id' => $clinic->id,
            'plan' => $clinic->subscription_plan,
            'status' => 'active',
            'price' => $clinic->subscription_plan === 'enterprise' ? 4500000 : 1500000,
            'usage_limits' => config('shiforeyting.plans.'.$clinic->subscription_plan),
        ]);

        Invoice::factory()->create([
            'clinic_id' => $clinic->id,
            'subscription_id' => $subscription->id,
            'status' => 'issued',
            'amount_due' => $subscription->price,
        ]);
    }

    private function seedResponses(Clinic $clinic, Survey $survey, Collection $questions, Collection $doctors, Collection $qrCodes): void
    {
        $positiveComments = [
            'Doctor was very polite and clear.',
            'Fast service and professional consultation.',
            'Waiting time was acceptable, thank you.',
        ];
        $negativeComments = [
            'Long waiting time and poor communication.',
            'I had to wait too much in queue.',
            'Explanation was not clear enough.',
        ];

        foreach ($doctors as $doctor) {
            $doctorQr = $qrCodes->firstWhere('doctor_id', $doctor->id);

            for ($i = 0; $i < 20; $i++) {
                $service = random_int(2, 5);
                $communication = random_int(2, 5);
                $waiting = random_int(1, 5);
                $explanation = random_int(2, 5);
                $wouldRecommend = random_int(0, 100) > 25;
                $severity = $wouldRecommend ? random_int(1, 2) : random_int(3, 5);
                $qualityScore = round((($service + $communication + $waiting + $explanation) / 20) * 100, 2);
                $sentiment = $wouldRecommend
                    ? round(mt_rand(20, 95) / 100, 2)
                    : round(mt_rand(-95, 20) / 100, 2);
                $isFlagged = $severity >= 4 && ! $wouldRecommend && random_int(0, 100) > 45;
                $comment = $wouldRecommend
                    ? $positiveComments[array_rand($positiveComments)]
                    : $negativeComments[array_rand($negativeComments)];

                $response = SurveyResponse::query()->create([
                    'clinic_id' => $clinic->id,
                    'branch_id' => $doctor->branch_id,
                    'department_id' => $doctor->department_id,
                    'doctor_id' => $doctor->id,
                    'service_point_id' => $doctorQr?->service_point_id,
                    'qr_code_id' => $doctorQr?->id,
                    'survey_id' => $survey->id,
                    'channel' => 'qr',
                    'submitted_at' => now()->subDays(random_int(0, 25))->subMinutes(random_int(1, 1200)),
                    'language' => collect(['uz_latn', 'ru', 'en'])->random(),
                    'ip_hash' => hash('sha256', 'demo-'.$doctor->id.'-'.$i),
                    'device_hash' => hash('sha256', 'device-'.$doctor->id.'-'.$i),
                    'fingerprint_hash' => hash('sha256', 'fp-'.$doctor->id.'-'.$i),
                    'fraud_score' => $isFlagged ? random_int(50, 80) : random_int(0, 25),
                    'anomaly_score' => $isFlagged ? random_int(30, 60) : random_int(0, 20),
                    'sentiment_score' => $sentiment,
                    'severity_score' => $severity,
                    'confidence_score' => $qualityScore,
                    'quality_score' => $qualityScore,
                    'is_flagged' => $isFlagged,
                    'moderation_status' => $isFlagged ? 'needs_review' : 'approved',
                    'is_duplicate' => false,
                    'callback_requested' => false,
                    'submitted_from_country' => 'UZ',
                    'ai_processed_at' => now(),
                ]);

                SurveyAnswer::query()->create([
                    'clinic_id' => $clinic->id,
                    'survey_response_id' => $response->id,
                    'survey_question_id' => $questions['service_quality']->id,
                    'question_type' => 'rating',
                    'rating_value' => $service,
                    'normalized_score' => $service * 20,
                ]);

                SurveyAnswer::query()->create([
                    'clinic_id' => $clinic->id,
                    'survey_response_id' => $response->id,
                    'survey_question_id' => $questions['communication']->id,
                    'question_type' => 'rating',
                    'rating_value' => $communication,
                    'normalized_score' => $communication * 20,
                ]);

                SurveyAnswer::query()->create([
                    'clinic_id' => $clinic->id,
                    'survey_response_id' => $response->id,
                    'survey_question_id' => $questions['waiting_experience']->id,
                    'question_type' => 'rating',
                    'rating_value' => $waiting,
                    'normalized_score' => $waiting * 20,
                ]);

                SurveyAnswer::query()->create([
                    'clinic_id' => $clinic->id,
                    'survey_response_id' => $response->id,
                    'survey_question_id' => $questions['explanation_quality']->id,
                    'question_type' => 'rating',
                    'rating_value' => $explanation,
                    'normalized_score' => $explanation * 20,
                ]);

                SurveyAnswer::query()->create([
                    'clinic_id' => $clinic->id,
                    'survey_response_id' => $response->id,
                    'survey_question_id' => $questions['would_recommend']->id,
                    'question_type' => 'recommend',
                    'boolean_value' => $wouldRecommend,
                    'normalized_score' => $wouldRecommend ? 100 : 0,
                ]);

                SurveyAnswer::query()->create([
                    'clinic_id' => $clinic->id,
                    'survey_response_id' => $response->id,
                    'survey_question_id' => $questions['complaint_severity']->id,
                    'question_type' => 'severity',
                    'severity_level' => $severity,
                    'normalized_score' => $severity * 20,
                ]);

                SurveyAnswer::query()->create([
                    'clinic_id' => $clinic->id,
                    'survey_response_id' => $response->id,
                    'survey_question_id' => $questions['comment']->id,
                    'question_type' => 'comment',
                    'text_answer' => $comment,
                ]);

                CommentAnalysis::query()->create([
                    'clinic_id' => $clinic->id,
                    'survey_response_id' => $response->id,
                    'language' => $response->language,
                    'original_comment' => $comment,
                    'cleaned_comment' => $comment,
                    'sentiment_label' => $wouldRecommend ? 'positive' : 'negative',
                    'sentiment_score' => $sentiment,
                    'toxicity_score' => $isFlagged ? 0.74 : 0.12,
                    'topics' => $wouldRecommend ? ['service', 'doctor'] : ['waiting_time', 'communication'],
                    'keywords' => $wouldRecommend ? ['fast', 'good'] : ['queue', 'slow'],
                    'summary' => $comment,
                    'coaching_suggestion' => $wouldRecommend
                        ? 'Maintain current communication style.'
                        : 'Improve waiting-time communication and clarity of explanations.',
                    'explained_flags' => $isFlagged ? [['type' => 'ai_anomaly', 'score' => 78, 'reason' => 'Critical severity and negative sentiment']] : [],
                    'model_version' => 'seed-v1',
                    'processed_at' => now(),
                ]);

                if ($isFlagged) {
                    SuspiciousFlag::query()->create([
                        'clinic_id' => $clinic->id,
                        'survey_response_id' => $response->id,
                        'flag_type' => 'ai_anomaly',
                        'score' => 78,
                        'reason' => 'Seeded critical anomaly.',
                        'evidence' => ['seed' => true],
                        'status' => 'open',
                    ]);

                    Escalation::query()->create([
                        'clinic_id' => $clinic->id,
                        'survey_response_id' => $response->id,
                        'doctor_id' => $doctor->id,
                        'branch_id' => $doctor->branch_id,
                        'department_id' => $doctor->department_id,
                        'severity' => 'critical',
                        'category' => 'patient_feedback',
                        'title' => 'Critical complaint requires review',
                        'description' => $comment,
                        'source' => 'auto',
                        'status' => 'open',
                        'opened_at' => now(),
                        'sla_due_at' => now()->addHours(8),
                    ]);
                }

                if ($doctorQr) {
                    QrScanEvent::query()->create([
                        'clinic_id' => $clinic->id,
                        'qr_code_id' => $doctorQr->id,
                        'channel' => 'qr',
                        'ip_hash' => $response->ip_hash,
                        'device_hash' => $response->device_hash,
                        'fingerprint_hash' => $response->fingerprint_hash,
                        'user_agent' => 'Seeder/1.0',
                        'language' => $response->language,
                        'scanned_at' => $response->submitted_at->copy()->subMinutes(1),
                        'converted_to_response_id' => $response->id,
                    ]);
                }
            }
        }
    }

    private function seedRatingsAndRewards(Clinic $clinic, Collection $doctors): void
    {
        $rule = RewardRule::factory()->create([
            'clinic_id' => $clinic->id,
            'name' => 'Top Monthly Doctor',
            'trigger_type' => 'rank',
            'conditions' => ['rank' => 1, 'minimum_feedback' => 20],
            'reward_type' => 'bonus',
            'reward_value' => 600000,
        ]);

        foreach ($doctors as $doctor) {
            $query = SurveyResponse::query()
                ->where('clinic_id', $clinic->id)
                ->where('doctor_id', $doctor->id)
                ->where('moderation_status', 'approved');

            $feedbackCount = (clone $query)->count();
            $qualityScore = (float) (clone $query)->avg('quality_score');
            $confidenceScore = (float) (clone $query)->avg('confidence_score');
            $sentimentScore = (float) (clone $query)->avg('sentiment_score');
            $flaggedCount = SurveyResponse::query()
                ->where('clinic_id', $clinic->id)
                ->where('doctor_id', $doctor->id)
                ->where('is_flagged', true)
                ->count();

            $snapshot = RatingSnapshot::query()->create([
                'clinic_id' => $clinic->id,
                'branch_id' => $doctor->branch_id,
                'department_id' => $doctor->department_id,
                'doctor_id' => $doctor->id,
                'period_type' => 'monthly',
                'period_start' => now()->startOfMonth()->toDateString(),
                'period_end' => now()->endOfMonth()->toDateString(),
                'feedback_count' => $feedbackCount,
                'flagged_count' => $flaggedCount,
                'quality_score' => round($qualityScore, 2),
                'confidence_adjusted_score' => round($confidenceScore, 2),
                'sentiment_score' => round($sentimentScore, 2),
            ]);

            if ($feedbackCount >= 20 && $confidenceScore >= 70) {
                Reward::query()->create([
                    'clinic_id' => $clinic->id,
                    'reward_rule_id' => $rule->id,
                    'rating_snapshot_id' => $snapshot->id,
                    'doctor_id' => $doctor->id,
                    'branch_id' => $doctor->branch_id,
                    'department_id' => $doctor->department_id,
                    'title' => 'Eligible for monthly performance bonus',
                    'description' => 'Auto-calculated based on confidence-adjusted score.',
                    'period_start' => now()->startOfMonth()->toDateString(),
                    'period_end' => now()->endOfMonth()->toDateString(),
                    'eligibility_score' => round($confidenceScore, 2),
                    'status' => 'eligible',
                    'amount' => 600000,
                    'currency' => 'UZS',
                ]);
            }
        }
    }

    private function seedLanguageAndAudit(Clinic $clinic): void
    {
        $keys = ['survey_welcome', 'survey_submit', 'dashboard_alerts'];
        $locales = ['uz_latn', 'uz_cyrl', 'ru', 'en'];

        foreach ($keys as $key) {
            foreach ($locales as $locale) {
                LanguageString::query()->create([
                    'clinic_id' => $clinic->id,
                    'namespace' => 'app',
                    'group' => 'custom',
                    'key' => $key,
                    'locale' => $locale,
                    'value' => $key.' '.$locale,
                    'is_active' => true,
                ]);
            }
        }

        AuditLog::query()->create([
            'clinic_id' => $clinic->id,
            'action' => 'seeded_demo_data',
            'auditable_type' => Clinic::class,
            'auditable_id' => $clinic->id,
            'new_values' => ['status' => 'seeded'],
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Seeder',
            'context' => ['source' => 'DemoDataSeeder'],
            'created_at' => now(),
        ]);
    }
}
