<?php

namespace App\Observers;

use App\Models\Doctor;
use App\Services\QrCodeService;

class DoctorObserver
{
    public function __construct(
        private readonly QrCodeService $qrCodeService,
    ) {}

    public function created(Doctor $doctor): void
    {
        $this->qrCodeService->syncForDoctor($doctor);
    }

    public function updated(Doctor $doctor): void
    {
        if (! $doctor->wasChanged(['clinic_id', 'branch_id', 'department_id', 'is_active'])) {
            return;
        }

        $this->qrCodeService->syncForDoctor($doctor);
    }

    public function deleted(Doctor $doctor): void
    {
        $this->qrCodeService->deactivateByTarget((int) $doctor->clinic_id, 'doctor', (int) $doctor->id);
    }

    public function restored(Doctor $doctor): void
    {
        $this->qrCodeService->syncForDoctor($doctor);
    }
}

