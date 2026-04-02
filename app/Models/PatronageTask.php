<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use App\Models\Concerns\HasClinicTenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatronageTask extends Model
{
    use HasFactory;
    use HasClinicTenant;
    use HasUuid;
    use SoftDeletes;

    protected $fillable = [
        'clinic_id',
        'hospital_clinic_id',
        'discharge_id',
        'patient_id',
        'family_doctor_id',
        'task_type',
        'priority',
        'status',
        'due_at',
        'notified_at',
        'accepted_at',
        'visited_at',
        'completed_at',
        'visit_notes',
        'visit_outcome',
        'patient_condition_score',
        'gps_latitude',
        'gps_longitude',
        'photo_proof_path',
        'escalation_level',
        'sla_breached',
        'sla_breach_minutes',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'due_at' => 'datetime',
            'notified_at' => 'datetime',
            'accepted_at' => 'datetime',
            'visited_at' => 'datetime',
            'completed_at' => 'datetime',
            'patient_condition_score' => 'integer',
            'gps_latitude' => 'decimal:7',
            'gps_longitude' => 'decimal:7',
            'escalation_level' => 'integer',
            'sla_breached' => 'boolean',
            'sla_breach_minutes' => 'integer',
            'meta' => 'array',
        ];
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function hospitalClinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class, 'hospital_clinic_id');
    }

    public function discharge(): BelongsTo
    {
        return $this->belongsTo(Discharge::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function familyDoctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'family_doctor_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(PatronageNotification::class);
    }

    public function isOverdue(): bool
    {
        return !$this->sla_breached
            && $this->due_at
            && $this->due_at->isPast()
            && !in_array($this->status, ['completed', 'missed']);
    }

    public function minutesOverdue(): int
    {
        return $this->due_at
            ? max(0, (int) now()->diffInMinutes($this->due_at, false))
            : 0;
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->whereIn('status', ['pending', 'notified', 'accepted', 'in_progress']);
    }

    public function scopeOverdue(Builder $query): Builder
    {
        return $query->where('due_at', '<', now())
            ->whereNotIn('status', ['completed', 'missed']);
    }
}
