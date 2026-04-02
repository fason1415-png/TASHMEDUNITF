<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use App\Models\Concerns\HasClinicTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use HasFactory;
    use HasClinicTenant;
    use HasUuid;
    use SoftDeletes;

    protected $fillable = [
        'clinic_id',
        'pinfl',
        'full_name',
        'birth_date',
        'gender',
        'phone',
        'address_region',
        'address_district',
        'address_text',
        'territorial_clinic_id',
        'family_doctor_id',
        'is_active',
        'meta',
    ];

    protected $hidden = [
        'pinfl',
        'phone',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'is_active' => 'boolean',
            'meta' => 'array',
        ];
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function territorialClinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class, 'territorial_clinic_id');
    }

    public function familyDoctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'family_doctor_id');
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
