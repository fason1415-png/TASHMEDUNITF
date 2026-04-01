<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use App\Models\Concerns\HasClinicTenant;
use Database\Factories\DoctorProfileFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DoctorProfile extends Model
{
    /** @use HasFactory<DoctorProfileFactory> */
    use HasFactory;
    use HasClinicTenant;
    use HasUuid;

    protected $fillable = [
        'clinic_id',
        'doctor_id',
        'education',
        'languages',
        'achievements',
        'strengths',
        'weaknesses',
        'ai_coaching_notes',
        'monthly_target_score',
    ];

    protected function casts(): array
    {
        return [
            'education' => 'array',
            'languages' => 'array',
            'achievements' => 'array',
            'strengths' => 'array',
            'weaknesses' => 'array',
            'monthly_target_score' => 'decimal:2',
        ];
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }
}
