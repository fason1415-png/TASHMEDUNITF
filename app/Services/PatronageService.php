<?php

namespace App\Services;

use App\Models\Discharge;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\PatronageTask;
use Illuminate\Support\Collection;

class PatronageService
{
    private const DEFAULT_VISIT_DAYS = [1, 3, 7, 14];

    private const SEVERITY_PRIORITY_MAP = [
        'critical' => 'urgent',
        'severe' => 'urgent',
        'moderate' => 'high',
        'mild' => 'normal',
    ];

    /**
     * Create patronage tasks from a discharge.
     * Called when a patient is discharged with requires_patronage=true.
     * Creates tasks based on recommended_visit_days (e.g. [1, 3, 7, 14]).
     *
     * @return Collection<int, PatronageTask>
     */
    public function createTasksFromDischarge(Discharge $discharge): Collection
    {
        if (! $discharge->requires_patronage) {
            return collect();
        }

        $familyDoctor = $this->resolveFamilyDoctor($discharge->patient);
        $visitDays = ! empty($discharge->recommended_visit_days) ? $discharge->recommended_visit_days : self::DEFAULT_VISIT_DAYS;
        $priority = self::SEVERITY_PRIORITY_MAP[$discharge->severity_level] ?? 'normal';

        $tasks = collect();

        foreach ($visitDays as $index => $days) {
            $task = PatronageTask::query()->create([
                'clinic_id' => $familyDoctor?->clinic_id ?? $discharge->patient->territorial_clinic_id,
                'hospital_clinic_id' => $discharge->clinic_id,
                'discharge_id' => $discharge->id,
                'patient_id' => $discharge->patient_id,
                'family_doctor_id' => $familyDoctor?->id,
                'task_type' => $index === 0 ? 'initial_visit' : 'follow_up',
                'priority' => $priority,
                'status' => 'pending',
                'due_at' => $discharge->discharged_at->addDays($days),
                'escalation_level' => 0,
                'sla_breached' => false,
            ]);

            $tasks->push($task);
        }

        return $tasks;
    }

    /**
     * Resolve the family doctor for a patient.
     * First checks patient.family_doctor_id, then tries to find by territorial region.
     */
    public function resolveFamilyDoctor(Patient $patient): ?Doctor
    {
        if ($patient->family_doctor_id) {
            return $patient->familyDoctor;
        }

        return Doctor::query()
            ->where('doctor_type', 'family')
            ->where('territorial_region', $patient->address_region)
            ->where('territorial_district', $patient->address_district)
            ->where('accepts_patronage', true)
            ->where('is_active', true)
            ->withCount(['patronageTasks' => function ($query): void {
                $query->whereIn('status', ['pending', 'notified', 'accepted', 'in_progress']);
            }])
            ->orderBy('patronage_tasks_count', 'asc')
            ->first();
    }

    /**
     * Doctor accepts a patronage task.
     */
    public function acceptTask(PatronageTask $task, Doctor $doctor): PatronageTask
    {
        abort_unless(
            in_array($task->status, ['pending', 'notified'], true),
            422,
            'Task can only be accepted when in pending or notified status.',
        );

        $task->update([
            'status' => 'accepted',
            'accepted_at' => now(),
            'family_doctor_id' => $doctor->id,
        ]);

        return $task->refresh();
    }

    /**
     * Confirm a visit with GPS, notes, and outcome.
     *
     * @param array{
     *     visit_notes: string|null,
     *     visit_outcome: string|null,
     *     patient_condition_score: int|null,
     *     gps_latitude: float|null,
     *     gps_longitude: float|null,
     *     photo_proof_path: string|null,
     * } $data
     */
    public function confirmVisit(PatronageTask $task, array $data): PatronageTask
    {
        abort_unless(
            in_array($task->status, ['accepted', 'in_progress'], true),
            422,
            'Task can only be completed when in accepted or in_progress status.',
        );

        $task->update([
            'status' => 'completed',
            'visited_at' => now(),
            'completed_at' => now(),
            'visit_notes' => $data['visit_notes'] ?? null,
            'visit_outcome' => $data['visit_outcome'] ?? null,
            'patient_condition_score' => $data['patient_condition_score'] ?? null,
            'gps_latitude' => $data['gps_latitude'] ?? null,
            'gps_longitude' => $data['gps_longitude'] ?? null,
            'photo_proof_path' => $data['photo_proof_path'] ?? null,
        ]);

        return $task->refresh();
    }

    /**
     * Schedule follow-up tasks from a discharge.
     * Usually called after initial visit is completed if more visits are needed.
     * Visit days are relative to now(), not the discharge date.
     *
     * @param int[] $visitDays
     * @return Collection<int, PatronageTask>
     */
    public function scheduleFollowUpTasks(Discharge $discharge, array $visitDays): Collection
    {
        $familyDoctor = $this->resolveFamilyDoctor($discharge->patient);
        $priority = self::SEVERITY_PRIORITY_MAP[$discharge->severity_level] ?? 'normal';

        $tasks = collect();

        foreach ($visitDays as $days) {
            $task = PatronageTask::query()->create([
                'clinic_id' => $familyDoctor?->clinic_id ?? $discharge->patient->territorial_clinic_id,
                'hospital_clinic_id' => $discharge->clinic_id,
                'discharge_id' => $discharge->id,
                'patient_id' => $discharge->patient_id,
                'family_doctor_id' => $familyDoctor?->id,
                'task_type' => 'follow_up',
                'priority' => $priority,
                'status' => 'pending',
                'due_at' => now()->addDays($days),
                'escalation_level' => 0,
                'sla_breached' => false,
            ]);

            $tasks->push($task);
        }

        return $tasks;
    }
}
