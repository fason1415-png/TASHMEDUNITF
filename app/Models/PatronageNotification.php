<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatronageNotification extends Model
{
    use HasFactory;
    use HasUuid;

    protected $fillable = [
        'patronage_task_id',
        'channel',
        'recipient_type',
        'recipient_id',
        'message_body',
        'status',
        'sent_at',
        'delivered_at',
        'attempt_count',
        'error_message',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
            'delivered_at' => 'datetime',
            'attempt_count' => 'integer',
            'meta' => 'array',
        ];
    }

    public function patronageTask(): BelongsTo
    {
        return $this->belongsTo(PatronageTask::class);
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }
}
