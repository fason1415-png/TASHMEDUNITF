<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use App\Models\Concerns\HasClinicTenant;
use Database\Factories\LanguageStringFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LanguageString extends Model
{
    /** @use HasFactory<LanguageStringFactory> */
    use HasFactory;
    use HasClinicTenant;
    use HasUuid;

    protected $fillable = [
        'clinic_id',
        'namespace',
        'group',
        'key',
        'locale',
        'value',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }
}
