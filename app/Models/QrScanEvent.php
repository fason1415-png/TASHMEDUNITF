<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use App\Models\Concerns\HasClinicTenant;
use Database\Factories\QrScanEventFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QrScanEvent extends Model
{
    /** @use HasFactory<QrScanEventFactory> */
    use HasFactory;
    use HasClinicTenant;
    use HasUuid;

    protected $fillable = [
        'clinic_id',
        'qr_code_id',
        'channel',
        'ip_hash',
        'device_hash',
        'fingerprint_hash',
        'user_agent',
        'language',
        'scanned_at',
        'converted_to_response_id',
    ];

    protected function casts(): array
    {
        return [
            'scanned_at' => 'datetime',
        ];
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function qrCode(): BelongsTo
    {
        return $this->belongsTo(QrCode::class);
    }

    public function convertedResponse(): BelongsTo
    {
        return $this->belongsTo(SurveyResponse::class, 'converted_to_response_id');
    }
}
