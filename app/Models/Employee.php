<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_code',
        'nama',
        'jabatan',
        'divisi',
        'department',
        'unit',
        'evaluator_id',
        'status',
    ];

    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }

    public function evaluation(): HasOne
    {
        return $this->hasOne(Evaluation::class, 'employee_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeAssignedTo($query, int $evaluatorId)
    {
        return $query->where('evaluator_id', $evaluatorId);
    }

    public function scopeSearch($query, ?string $term)
    {
        if (empty($term)) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('employee_code', 'like', "%{$term}%")
              ->orWhere('nama', 'like', "%{$term}%")
              ->orWhere('jabatan', 'like', "%{$term}%")
              ->orWhere('divisi', 'like', "%{$term}%");
        });
    }

    public function scopeFilterDivisi($query, ?string $divisi)
    {
        if (empty($divisi)) {
            return $query;
        }

        return $query->where('divisi', $divisi);
    }

    public function scopeFilterEvaluator($query, $evaluatorId)
    {
        if (empty($evaluatorId)) {
            return $query;
        }

        if ($evaluatorId === 'unassigned') {
            return $query->whereNull('evaluator_id');
        }

        return $query->where('evaluator_id', $evaluatorId);
    }
}
