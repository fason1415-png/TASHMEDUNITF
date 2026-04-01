<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use App\Models\Concerns\HasClinicTenant;
use Database\Factories\RewardFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reward extends Model
{
    /** @use HasFactory<RewardFactory> */
    use HasFactory;
    use HasClinicTenant;
    use HasUuid;

    protected $fillable = [
        'clinic_id',
        'reward_rule_id',
        'rating_snapshot_id',
        'doctor_id',
        'branch_id',
        'department_id',
        'title',
        'description',
        'period_start',
        'period_end',
        'eligibility_score',
        'status',
        'approved_by',
        'approved_at',
        'paid_at',
        'amount',
        'currency',
        'notes',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'period_start' => 'date',
            'period_end' => 'date',
            'eligibility_score' => 'decimal:2',
            'amount' => 'decimal:2',
            'approved_at' => 'datetime',
            'paid_at' => 'datetime',
            'meta' => 'array',
        ];
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function rule(): BelongsTo
    {
        return $this->belongsTo(RewardRule::class, 'reward_rule_id');
    }

    public function snapshot(): BelongsTo
    {
        return $this->belongsTo(RatingSnapshot::class, 'rating_snapshot_id');
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
