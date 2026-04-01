<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use App\Models\Concerns\HasClinicTenant;
use Database\Factories\RatingSnapshotFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RatingSnapshot extends Model
{
    /** @use HasFactory<RatingSnapshotFactory> */
    use HasFactory;
    use HasClinicTenant;
    use HasUuid;

    protected $fillable = [
        'clinic_id',
        'branch_id',
        'department_id',
        'doctor_id',
        'period_type',
        'period_start',
        'period_end',
        'feedback_count',
        'flagged_count',
        'quality_score',
        'confidence_adjusted_score',
        'sentiment_score',
        'service_quality_score',
        'communication_score',
        'wait_time_score',
        'explanation_score',
        'resolution_score',
        'nps_score',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'period_start' => 'date',
            'period_end' => 'date',
            'quality_score' => 'decimal:2',
            'confidence_adjusted_score' => 'decimal:2',
            'sentiment_score' => 'decimal:2',
            'service_quality_score' => 'decimal:2',
            'communication_score' => 'decimal:2',
            'wait_time_score' => 'decimal:2',
            'explanation_score' => 'decimal:2',
            'resolution_score' => 'decimal:2',
            'nps_score' => 'decimal:2',
            'meta' => 'array',
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

    public function rewards(): HasMany
    {
        return $this->hasMany(Reward::class);
    }
}
