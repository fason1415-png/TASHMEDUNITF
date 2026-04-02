<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\PatronageTask;
use App\Services\PatronageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PatronageApiController extends Controller
{
    public function __construct(
        private PatronageService $patronageService,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $doctor = $request->user()?->doctor;

        if (! $doctor) {
            return response()->json(['message' => 'Doctor profile not found.'], 403);
        }

        $tasks = PatronageTask::query()
            ->where('family_doctor_id', $doctor->id)
            ->with(['patient:id,full_name,phone,address_region,address_district,address_text', 'discharge:id,diagnosis_code,diagnosis_text,severity_level'])
            ->orderByDesc('due_at')
            ->paginate(20);

        return response()->json($tasks);
    }

    public function show(Request $request, string $uuid): JsonResponse
    {
        $doctor = $request->user()?->doctor;

        if (! $doctor) {
            return response()->json(['message' => 'Doctor profile not found.'], 403);
        }

        $task = PatronageTask::query()
            ->where('uuid', $uuid)
            ->where('family_doctor_id', $doctor->id)
            ->with([
                'patient:id,full_name,phone,address_region,address_district,address_text',
                'discharge:id,diagnosis_code,diagnosis_text,severity_level,discharge_type,recommended_visit_days,discharged_at',
                'hospitalClinic:id,name,address',
                'notifications',
            ])
            ->firstOrFail();

        return response()->json($task);
    }

    public function accept(Request $request, string $uuid): JsonResponse
    {
        $doctor = $request->user()?->doctor;

        if (! $doctor) {
            return response()->json(['message' => 'Doctor profile not found.'], 403);
        }

        $task = PatronageTask::query()
            ->where('uuid', $uuid)
            ->where('family_doctor_id', $doctor->id)
            ->firstOrFail();

        $task = $this->patronageService->acceptTask($task, $doctor);

        return response()->json(['message' => 'Task accepted.', 'task' => $task]);
    }

    public function confirmVisit(Request $request, string $uuid): JsonResponse
    {
        $doctor = $request->user()?->doctor;

        if (! $doctor) {
            return response()->json(['message' => 'Doctor profile not found.'], 403);
        }

        $task = PatronageTask::query()
            ->where('uuid', $uuid)
            ->where('family_doctor_id', $doctor->id)
            ->firstOrFail();

        $validated = $request->validate([
            'visit_notes' => ['nullable', 'string', 'max:2000'],
            'visit_outcome' => ['nullable', 'string', 'max:1000'],
            'patient_condition_score' => ['required', 'integer', 'between:1,5'],
            'gps_latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'gps_longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'photo_proof_path' => ['nullable', 'string', 'max:500'],
        ]);

        $task = $this->patronageService->confirmVisit($task, $validated);

        return response()->json(['message' => 'Visit confirmed.', 'task' => $task]);
    }

    public function stats(Request $request): JsonResponse
    {
        $doctor = $request->user()?->doctor;

        if (! $doctor) {
            return response()->json(['message' => 'Doctor profile not found.'], 403);
        }

        $query = PatronageTask::query()->where('family_doctor_id', $doctor->id);

        return response()->json([
            'total' => (clone $query)->count(),
            'pending' => (clone $query)->whereIn('status', ['pending', 'notified'])->count(),
            'accepted' => (clone $query)->where('status', 'accepted')->count(),
            'in_progress' => (clone $query)->where('status', 'in_progress')->count(),
            'completed' => (clone $query)->where('status', 'completed')->count(),
            'missed' => (clone $query)->where('status', 'missed')->count(),
            'escalated' => (clone $query)->where('status', 'escalated')->count(),
            'sla_breached' => (clone $query)->where('sla_breached', true)->count(),
        ]);
    }
}
