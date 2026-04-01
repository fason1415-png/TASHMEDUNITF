<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use App\Models\Concerns\HasClinicTenant;
use Database\Factories\RewardRuleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RewardRule extends Model
{
    /** @use HasFactory<RewardRuleFactory> */
    use HasFactory;
    use HasClinicTenant;
    use HasUuid;

    protected $fillable = [
        'clinic_id',
        'name',
        'description',
        'trigger_type',
        'conditions',
        'reward_type',
        'reward_value',
        'reward_meta',
        'period_type',
        'is_active',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'conditions' => 'array',
            'reward_meta' => 'array',
            'reward_value' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function rewards(): HasMany
    {
        return $this->hasMany(Reward::class);
    }
}
