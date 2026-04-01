<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use App\Models\Concerns\HasClinicTenant;
use Database\Factories\SuspiciousFlagFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuspiciousFlag extends Model
{
    /** @use HasFactory<SuspiciousFlagFactory> */
    use HasFactory;
    use HasClinicTenant;
    use HasUuid;

    protected $fillable = [
        'clinic_id',
        'survey_response_id',
        'flag_type',
        'score',
        'reason',
        'evidence',
        'status',
        'reviewed_by',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'evidence' => 'array',
            'score' => 'decimal:2',
            'reviewed_at' => 'datetime',
        ];
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function response(): BelongsTo
    {
        return $this->belongsTo(SurveyResponse::class, 'survey_response_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
