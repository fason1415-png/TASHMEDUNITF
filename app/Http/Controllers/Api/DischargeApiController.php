<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Events\PatientDischarged;
use App\Models\Discharge;
use App\Models\Patient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DischargeApiController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'patient_pinfl' => ['required', 'string', 'size:14'],
            'patient_name' => ['required', 'string', 'max:255'],
            'patient_phone' => ['nullable', 'string', 'max:20'],
            'patient_birth_date' => ['nullable', 'date'],
            'patient_gender' => ['nullable', 'in:male,female'],
            'patient_address_region' => ['nullable', 'string', 'max:255'],
            'patient_address_district' => ['nullable', 'string', 'max:255'],
            'patient_address_text' => ['nullable', 'string', 'max:1000'],
            'branch_id' => ['nullable', 'exists:branches,id'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'attending_doctor_id' => ['nullable', 'exists:doctors,id'],
            'diagnosis_code' => ['nullable', 'string', 'max:20'],
            'diagnosis_text' => ['nullable', 'string', 'max:2000'],
            'severity_level' => ['required', 'in:mild,moderate,severe,critical'],
            'discharge_type' => ['required', 'in:recovery,improvement,transfer,against_advice,death'],
            'requires_patronage' => ['required', 'boolean'],
            'recommended_visit_days' => ['nullable', 'array'],
            'recommended_visit_days.*' => ['integer', 'min:1', 'max:90'],
            'discharged_at' => ['nullable', 'date'],
        ]);

        $clinicId = $request->user()?->clinic_id;

        if (! $clinicId) {
            return response()->json(['message' => 'Clinic context required.'], 403);
        }

        $patient = Patient::updateOrCreate(
            ['pinfl' => $validated['patient_pinfl']],
            [
                'clinic_id' => $clinicId,
                'full_name' => $validated['patient_name'],
                'phone' => $validated['patient_phone'] ?? null,
                'birth_date' => $validated['patient_birth_date'] ?? null,
                'gender' => $validated['patient_gender'] ?? null,
                'address_region' => $validated['patient_address_region'] ?? null,
                'address_district' => $validated['patient_address_district'] ?? null,
                'address_text' => $validated['patient_address_text'] ?? null,
            ],
        );

        $discharge = Discharge::create([
            'clinic_id' => $clinicId,
            'patient_id' => $patient->id,
            'branch_id' => $validated['branch_id'] ?? null,
            'department_id' => $validated['department_id'] ?? null,
            'attending_doctor_id' => $validated['attending_doctor_id'] ?? null,
            'diagnosis_code' => $validated['diagnosis_code'] ?? null,
            'diagnosis_text' => $validated['diagnosis_text'] ?? null,
            'severity_level' => $validated['severity_level'],
            'discharge_type' => $validated['discharge_type'],
            'requires_patronage' => $validated['requires_patronage'],
            'recommended_visit_days' => $validated['recommended_visit_days'] ?? [1, 3, 7, 14],
            'discharged_at' => $validated['discharged_at'] ?? now(),
            'created_by' => $request->user()?->id,
        ]);

        PatientDischarged::dispatch($discharge);

        return response()->json([
            'message' => 'Discharge recorded successfully.',
            'discharge' => $discharge->load('patient'),
        ], 201);
    }
}
