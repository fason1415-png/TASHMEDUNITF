<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use App\Models\Concerns\HasClinicTenant;
use Database\Factories\QrCodeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class QrCode extends Model
{
    /** @use HasFactory<QrCodeFactory> */
    use HasFactory;
    use HasClinicTenant;
    use HasUuid;
    use SoftDeletes;

    protected $fillable = [
        'clinic_id',
        'branch_id',
        'department_id',
        'doctor_id',
        'service_point_id',
        'target_type',
        'target_id',
        'code',
        'token',
        'short_url',
        'is_active',
        'meta',
        'scan_count',
        'printed_at',
        'last_scanned_at',
        'expires_at',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'meta' => 'array',
            'printed_at' => 'datetime',
            'last_scanned_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function servicePoint(): BelongsTo
    {
        return $this->belongsTo(ServicePoint::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function responses(): HasMany
    {
        return $this->hasMany(SurveyResponse::class);
    }

    public function scans(): HasMany
    {
        return $this->hasMany(QrScanEvent::class);
    }
}
