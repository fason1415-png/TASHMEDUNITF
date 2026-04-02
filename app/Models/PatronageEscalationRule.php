<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use App\Models\Concerns\HasClinicTenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatronageEscalationRule extends Model
{
    use HasFactory;
    use HasClinicTenant;
    use HasUuid;

    protected $fillable = [
        'clinic_id',
        'escalation_level',
        'trigger_after_minutes',
        'notify_role',
        'notification_channels',
        'auto_reassign',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'escalation_level' => 'integer',
            'trigger_after_minutes' => 'integer',
            'notification_channels' => 'array',
            'auto_reassign' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeForLevel(Builder $query, int $level): Builder
    {
        return $query->where('escalation_level', $level);
    }
}
