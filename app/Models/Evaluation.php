<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'evaluator_id',
        'status',
        'total_score',
        'strengths',
        'areas_for_improvement',
        'recommendations',
        'evaluator_notes',
        'submitted_at',
        'unlocked_by',
        'unlocked_at',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'unlocked_at' => 'datetime',
            'total_score' => 'decimal:2',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }

    public function unlockedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'unlocked_by');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(EvaluationAnswer::class, 'evaluation_id');
    }

    public function isSubmitted(): bool
    {
        return $this->status === 'submitted';
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function recalculateTotalScore(): float
    {
        $avg = $this->answers()->whereNotNull('score')->avg('score');
        $this->total_score = $avg ? round($avg, 2) : 0;
        $this->save();
        return (float) $this->total_score;
    }
}
