<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Database\Factories\ClinicFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Clinic extends Model
{
    /** @use HasFactory<ClinicFactory> */
    use HasFactory;
    use HasUuid;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'legal_name',
        'region',
        'city',
        'address',
        'phone',
        'email',
        'logo_path',
        'branding',
        'scoring_weights',
        'ai_settings',
        'min_public_samples',
        'subscription_plan',
        'trial_ends_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'branding' => 'array',
            'scoring_weights' => 'array',
            'ai_settings' => 'array',
            'is_active' => 'boolean',
            'trial_ends_at' => 'datetime',
        ];
    }

    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class);
    }

    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }

    public function doctors(): HasMany
    {
        return $this->hasMany(Doctor::class);
    }

    public function servicePoints(): HasMany
    {
        return $this->hasMany(ServicePoint::class);
    }

    public function qrCodes(): HasMany
    {
        return $this->hasMany(QrCode::class);
    }

    public function surveys(): HasMany
    {
        return $this->hasMany(Survey::class);
    }

    public function responses(): HasMany
    {
        return $this->hasMany(SurveyResponse::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function patients(): HasMany
    {
        return $this->hasMany(Patient::class);
    }

    public function discharges(): HasMany
    {
        return $this->hasMany(Discharge::class);
    }

    public function patronageTasks(): HasMany
    {
        return $this->hasMany(PatronageTask::class);
    }
}

