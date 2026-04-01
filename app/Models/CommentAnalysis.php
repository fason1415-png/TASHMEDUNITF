<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use App\Models\Concerns\HasClinicTenant;
use Database\Factories\CommentAnalysisFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommentAnalysis extends Model
{
    /** @use HasFactory<CommentAnalysisFactory> */
    use HasFactory;
    use HasClinicTenant;
    use HasUuid;

    protected $table = 'comment_analysis';

    protected $fillable = [
        'clinic_id',
        'survey_response_id',
        'language',
        'original_comment',
        'cleaned_comment',
        'sentiment_label',
        'sentiment_score',
        'toxicity_score',
        'topics',
        'keywords',
        'summary',
        'coaching_suggestion',
        'explained_flags',
        'model_version',
        'processed_at',
    ];

    protected function casts(): array
    {
        return [
            'topics' => 'array',
            'keywords' => 'array',
            'explained_flags' => 'array',
            'sentiment_score' => 'decimal:2',
            'toxicity_score' => 'decimal:2',
            'processed_at' => 'datetime',
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
}
