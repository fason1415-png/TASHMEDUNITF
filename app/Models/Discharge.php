<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use App\Models\Concerns\HasClinicTenant;
use App\Observers\DischargeObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy(DischargeObserver::class)]
class Discharge extends Model
{
    use HasFactory;
    use HasClinicTenant;
    use HasUuid;
    use SoftDeletes;

    protected $fillable = [
        'clinic_id',
        'patient_id',
        'branch_id',
        'department_id',
        'attending_doctor_id',
        'diagnosis_code',
        'diagnosis_text',
        'severity_level',
        'discharge_type',
        'requires_patronage',
        'recommended_visit_days',
        'discharged_at',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'requires_patronage' => 'boolean',
            'recommended_visit_days' => 'array',
            'discharged_at' => 'datetime',
        ];
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function attendingDoctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'attending_doctor_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function patronageTasks(): HasMany
    {
        return $this->hasMany(PatronageTask::class);
    }
}
