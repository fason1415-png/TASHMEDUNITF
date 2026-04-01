<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use App\Models\Concerns\HasClinicTenant;
use Database\Factories\SurveyResponseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SurveyResponse extends Model
{
    /** @use HasFactory<SurveyResponseFactory> */
    use HasFactory;
    use HasClinicTenant;
    use HasUuid;

    protected $fillable = [
        'clinic_id',
        'branch_id',
        'department_id',
        'doctor_id',
        'service_point_id',
        'qr_code_id',
        'survey_id',
        'channel',
        'submitted_at',
        'language',
        'ip_hash',
        'device_hash',
        'fingerprint_hash',
        'verified_token',
        'fraud_score',
        'anomaly_score',
        'sentiment_score',
        'severity_score',
        'confidence_score',
        'quality_score',
        'is_flagged',
        'moderation_status',
        'is_duplicate',
        'duplicate_of_response_id',
        'callback_requested',
        'callback_contact',
        'callback_note',
        'submitted_from_country',
        'ai_processed_at',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'fraud_score' => 'decimal:2',
            'anomaly_score' => 'decimal:2',
            'sentiment_score' => 'decimal:2',
            'severity_score' => 'decimal:2',
            'confidence_score' => 'decimal:2',
            'quality_score' => 'decimal:2',
            'is_flagged' => 'boolean',
            'is_duplicate' => 'boolean',
            'callback_requested' => 'boolean',
            'ai_processed_at' => 'datetime',
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

    public function qrCode(): BelongsTo
    {
        return $this->belongsTo(QrCode::class);
    }

    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class);
    }

    public function duplicateOf(): BelongsTo
    {
        return $this->belongsTo(self::class, 'duplicate_of_response_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(SurveyAnswer::class);
    }

    public function commentAnalysis(): HasOne
    {
        return $this->hasOne(CommentAnalysis::class);
    }

    public function suspiciousFlags(): HasMany
    {
        return $this->hasMany(SuspiciousFlag::class);
    }
}
