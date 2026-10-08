<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\User;

class EmployeePolicy
{
    /**
     * Determine whether the user can view any employees.
     */
    public function viewAny(User $user): bool
    {
        return true; // Admin views all, evaluator views filtered by controller scope
    }

    /**
     * Determine whether the user can view the employee.
     */
    public function view(User $user, Employee $employee): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $employee->evaluator_id === $user->id;
    }

    /**
     * Determine whether the user can create employees.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the employee.
     */
    public function update(User $user, Employee $employee): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the employee.
     */
    public function delete(User $user, Employee $employee): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can evaluate the employee.
     */
    public function evaluate(User $user, Employee $employee): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $employee->evaluator_id === $user->id;
    }
}
