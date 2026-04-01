<?php

namespace App\Observers;

use App\Models\Department;
use App\Services\QrCodeService;

class DepartmentObserver
{
    public function __construct(
        private readonly QrCodeService $qrCodeService,
    ) {}

    public function created(Department $department): void
    {
        $this->qrCodeService->syncForDepartment($department);
    }

    public function updated(Department $department): void
    {
        if (! $department->wasChanged(['clinic_id', 'branch_id', 'is_active'])) {
            return;
        }

        $this->qrCodeService->syncForDepartment($department);
    }

    public function deleted(Department $department): void
    {
        $this->qrCodeService->deactivateByTarget((int) $department->clinic_id, 'department', (int) $department->id);
    }

    public function restored(Department $department): void
    {
        $this->qrCodeService->syncForDepartment($department);
    }
}

