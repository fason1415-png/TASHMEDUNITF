<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use App\Models\Concerns\HasClinicTenant;
use Database\Factories\DoctorFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Doctor extends Model
{
    /** @use HasFactory<DoctorFactory> */
    use HasFactory;
    use HasClinicTenant;
    use HasUuid;
    use SoftDeletes;

    protected $fillable = [
        'clinic_id',
        'branch_id',
        'department_id',
        'full_name',
        'specialty',
        'status',
        'photo',
        'experience_years',
        'bio',
        'consultation_type',
        'is_active',
        'hired_at',
        'left_at',
        'doctor_type',
        'territorial_region',
        'territorial_district',
        'telegram_chat_id',
        'phone',
        'push_token',
        'accepts_patronage',
        'max_active_patronage',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'hired_at' => 'date',
            'left_at' => 'date',
            'accepts_patronage' => 'boolean',
            'max_active_patronage' => 'integer',
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

    public function profile(): HasOne
    {
        return $this->hasOne(DoctorProfile::class);
    }

    public function qrCodes(): HasMany
    {
        return $this->hasMany(QrCode::class);
    }

    public function responses(): HasMany
    {
        return $this->hasMany(SurveyResponse::class);
    }

    public function ratingSnapshots(): HasMany
    {
        return $this->hasMany(RatingSnapshot::class);
    }

    public function patronageTasks(): HasMany
    {
        return $this->hasMany(PatronageTask::class, 'family_doctor_id');
    }

    public function patients(): HasMany
    {
        return $this->hasMany(Patient::class, 'family_doctor_id');
    }
}
