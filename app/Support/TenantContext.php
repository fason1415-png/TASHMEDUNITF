<?php

namespace App\Support;

class TenantContext
{
    public function __construct(
        protected ?int $clinicId = null,
    ) {
    }

    public function setClinicId(?int $clinicId): void
    {
        $this->clinicId = $clinicId;
    }

    public function clinicId(): ?int
    {
        return $this->clinicId;
    }

    public function hasClinic(): bool
    {
        return $this->clinicId !== null;
    }
}

