<?php

namespace App\Policies;

use App\Models\Evaluation;
use App\Models\User;

class EvaluationPolicy
{
    /**
     * Determine whether the user can view any evaluations.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the evaluation.
     */
    public function view(User $user, Evaluation $evaluation): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $evaluation->evaluator_id === $user->id;
    }

    /**
     * Determine whether the user can update / save draft / submit the evaluation.
     */
    public function update(User $user, Evaluation $evaluation): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        // Evaluator can only edit if assigned and status is draft
        return $evaluation->evaluator_id === $user->id && $evaluation->isDraft();
    }

    /**
     * Determine whether the user can unlock a submitted evaluation.
     */
    public function unlock(User $user, Evaluation $evaluation): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can print the evaluation report.
     */
    public function print(User $user, Evaluation $evaluation): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $evaluation->evaluator_id === $user->id;
    }
}
