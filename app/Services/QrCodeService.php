<?php

namespace App\Services;

use App\Models\Department;
use App\Models\Doctor;
use App\Models\QrCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeFacade;
use Throwable;

class QrCodeService
{
    /**
     * @param array<string,mixed> $attributes
     */
    public function create(array $attributes): QrCode
    {
        $targetType = (string) ($attributes['target_type'] ?? 'generic');
        $clinicId = (int) $attributes['clinic_id'];

        return QrCode::query()->create([
            ...$attributes,
            'clinic_id' => $clinicId,
            'target_type' => $targetType,
            'code' => $attributes['code'] ?? strtoupper(Str::random(10)),
            'token' => $attributes['token'] ?? Str::random(40),
            'is_active' => $attributes['is_active'] ?? true,
        ]);
    }

    public function syncForDoctor(Doctor $doctor): QrCode
    {
        return $this->upsertByTarget([
            'clinic_id' => (int) $doctor->clinic_id,
            'branch_id' => $doctor->branch_id,
            'department_id' => $doctor->department_id,
            'doctor_id' => $doctor->id,
            'service_point_id' => null,
            'target_type' => 'doctor',
            'target_id' => $doctor->id,
            'is_active' => (bool) $doctor->is_active,
            'meta' => [
                'source' => 'auto',
                'entity' => 'doctor',
                'entity_uuid' => $doctor->uuid,
            ],
        ]);
    }

    public function syncForDepartment(Department $department): QrCode
    {
        return $this->upsertByTarget([
            'clinic_id' => (int) $department->clinic_id,
            'branch_id' => $department->branch_id,
            'department_id' => $department->id,
            'doctor_id' => null,
            'service_point_id' => null,
            'target_type' => 'department',
            'target_id' => $department->id,
            'is_active' => (bool) $department->is_active,
            'meta' => [
                'source' => 'auto',
                'entity' => 'department',
                'entity_uuid' => $department->uuid,
            ],
        ]);
    }

    /**
     * @param array{
     *     clinic_id:int,
     *     target_type:string,
     *     target_id:int,
     *     branch_id:int|null,
     *     department_id:int|null,
     *     doctor_id:int|null,
     *     service_point_id:int|null,
     *     is_active:bool,
     *     meta:array<string,mixed>
     * } $attributes
     */
    public function upsertByTarget(array $attributes): QrCode
    {
        $clinicId = (int) $attributes['clinic_id'];
        $targetType = (string) $attributes['target_type'];
        $targetId = (int) $attributes['target_id'];

        $record = QrCode::query()
            ->withTrashed()
            ->where('clinic_id', $clinicId)
            ->where('target_type', $targetType)
            ->where('target_id', $targetId)
            ->orderByDesc('id')
            ->first();

        $payload = [
            'clinic_id' => $clinicId,
            'branch_id' => $attributes['branch_id'] ?? null,
            'department_id' => $attributes['department_id'] ?? null,
            'doctor_id' => $attributes['doctor_id'] ?? null,
            'service_point_id' => $attributes['service_point_id'] ?? null,
            'target_type' => $targetType,
            'target_id' => $targetId,
            'is_active' => (bool) ($attributes['is_active'] ?? true),
            'meta' => $attributes['meta'] ?? null,
        ];

        if ($record) {
            if ($record->trashed()) {
                $record->restore();
            }

            if (! $record->code) {
                $record->code = $this->generateUniqueCode($targetType, $clinicId, $targetId);
            }

            if (! $record->token) {
                $record->token = $this->generateUniqueToken($targetType, $clinicId, $targetId);
            }

            $record->fill($payload);
            $record->save();

            return $record->fresh();
        }

        return QrCode::query()->create([
            ...$payload,
            'code' => $this->generateUniqueCode($targetType, $clinicId, $targetId),
            'token' => $this->generateUniqueToken($targetType, $clinicId, $targetId),
            'created_by' => Auth::id(),
        ]);
    }

    public function deactivateByTarget(int $clinicId, string $targetType, int $targetId): void
    {
        QrCode::query()
            ->where('clinic_id', $clinicId)
            ->where('target_type', $targetType)
            ->where('target_id', $targetId)
            ->update(['is_active' => false]);
    }

    private function generateUniqueCode(string $targetType, int $clinicId, int $targetId): string
    {
        $prefix = $this->targetPrefix($targetType);
        $base = sprintf('%s-%04d-%06d', $prefix, $clinicId, $targetId);
        $candidate = $base;

        while (QrCode::query()->withTrashed()->where('code', $candidate)->exists()) {
            $candidate = $base.'-'.strtoupper(Str::random(4));
        }

        return $candidate;
    }

    private function generateUniqueToken(string $targetType, int $clinicId, int $targetId): string
    {
        $prefix = strtolower($this->targetPrefix($targetType));
        $base = $prefix.'-'.$clinicId.'-'.$targetId.'-';
        $candidate = substr($base.Str::lower(Str::random(24)), 0, 64);

        while (QrCode::query()->withTrashed()->where('token', $candidate)->exists()) {
            $candidate = substr($base.Str::lower(Str::random(24)), 0, 64);
        }

        return $candidate;
    }

    private function targetPrefix(string $targetType): string
    {
        return match ($targetType) {
            'doctor' => 'DOC',
            'department' => 'DEP',
            'branch' => 'BRN',
            'room' => 'ROM',
            'service_type' => 'SRV',
            default => 'GEN',
        };
    }

    public function renderSvg(string $url, int $size = 300): string
    {
        return QrCodeFacade::format('svg')->size($size)->generate($url);
    }

    public function buildSurveyUrl(string $token): string
    {
        $baseUrl = trim((string) config('shiforeyting.qr_public_base_url'));

        if ($baseUrl === '') {
            $baseUrl = url('/');
        }

        return rtrim($baseUrl, '/').'/f/'.rawurlencode($token);
    }

    public function renderImageDataUri(string $url, int $size = 300): string
    {
        try {
            $pngBinary = QrCodeFacade::format('png')->size($size)->margin(1)->generate($url);

            return 'data:image/png;base64,'.base64_encode($pngBinary);
        } catch (Throwable) {
            $svg = $this->renderSvg($url, $size);

            return 'data:image/svg+xml;base64,'.base64_encode($svg);
        }
    }
}
