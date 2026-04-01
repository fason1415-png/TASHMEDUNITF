<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Clinic;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\QrCode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AutoQrCodeGenerationTest extends TestCase
{
    use RefreshDatabase;

    public function test_department_creation_automatically_generates_qr_code(): void
    {
        $clinic = Clinic::factory()->create();
        $branch = Branch::factory()->create(['clinic_id' => $clinic->id]);

        $department = Department::factory()->create([
            'clinic_id' => $clinic->id,
            'branch_id' => $branch->id,
        ]);

        $qrCode = QrCode::query()
            ->where('clinic_id', $clinic->id)
            ->where('target_type', 'department')
            ->where('target_id', $department->id)
            ->first();

        $this->assertNotNull($qrCode);
        $this->assertSame($department->id, $qrCode->department_id);
        $this->assertSame($branch->id, $qrCode->branch_id);
        $this->assertTrue(str_starts_with($qrCode->code, 'DEP-'));
    }

    public function test_doctor_creation_automatically_generates_qr_code(): void
    {
        $clinic = Clinic::factory()->create();
        $branch = Branch::factory()->create(['clinic_id' => $clinic->id]);
        $department = Department::factory()->create([
            'clinic_id' => $clinic->id,
            'branch_id' => $branch->id,
        ]);

        $doctor = Doctor::factory()->create([
            'clinic_id' => $clinic->id,
            'branch_id' => $branch->id,
            'department_id' => $department->id,
        ]);

        $qrCode = QrCode::query()
            ->where('clinic_id', $clinic->id)
            ->where('target_type', 'doctor')
            ->where('target_id', $doctor->id)
            ->first();

        $this->assertNotNull($qrCode);
        $this->assertSame($doctor->id, $qrCode->doctor_id);
        $this->assertSame($department->id, $qrCode->department_id);
        $this->assertSame($branch->id, $qrCode->branch_id);
        $this->assertTrue(str_starts_with($qrCode->code, 'DOC-'));
    }

    public function test_doctor_qr_code_is_synced_without_duplicate_on_update(): void
    {
        $clinic = Clinic::factory()->create();
        $branchA = Branch::factory()->create(['clinic_id' => $clinic->id]);
        $branchB = Branch::factory()->create(['clinic_id' => $clinic->id]);
        $departmentA = Department::factory()->create([
            'clinic_id' => $clinic->id,
            'branch_id' => $branchA->id,
        ]);
        $departmentB = Department::factory()->create([
            'clinic_id' => $clinic->id,
            'branch_id' => $branchB->id,
        ]);

        $doctor = Doctor::factory()->create([
            'clinic_id' => $clinic->id,
            'branch_id' => $branchA->id,
            'department_id' => $departmentA->id,
        ]);

        $doctor->update([
            'branch_id' => $branchB->id,
            'department_id' => $departmentB->id,
        ]);

        $count = QrCode::query()
            ->where('clinic_id', $clinic->id)
            ->where('target_type', 'doctor')
            ->where('target_id', $doctor->id)
            ->count();

        $qrCode = QrCode::query()
            ->where('clinic_id', $clinic->id)
            ->where('target_type', 'doctor')
            ->where('target_id', $doctor->id)
            ->first();

        $this->assertSame(1, $count);
        $this->assertNotNull($qrCode);
        $this->assertSame($branchB->id, $qrCode->branch_id);
        $this->assertSame($departmentB->id, $qrCode->department_id);
    }
}

