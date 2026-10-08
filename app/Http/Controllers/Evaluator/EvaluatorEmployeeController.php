<?php

namespace App\Http\Controllers\Evaluator;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class EvaluatorEmployeeController extends Controller
{
    public function index(Request $request): View
    {
        $evaluatorId = Auth::id();
        $search = $request->input('search');
        $statusFilter = $request->input('status');

        $query = Employee::assignedTo($evaluatorId)->with('evaluation');

        if ($search) {
            $query->search($search);
        }

        if ($statusFilter) {
            if ($statusFilter === 'submitted') {
                $query->whereHas('evaluation', function ($q) {
                    $q->where('status', 'submitted');
                });
            } elseif ($statusFilter === 'draft') {
                $query->whereHas('evaluation', function ($q) {
                    $q->where('status', 'draft');
                });
            } elseif ($statusFilter === 'none') {
                $query->whereDoesntHave('evaluation');
            }
        }

        $employees = $query->orderBy('nama')->paginate(20)->withQueryString();

        return view('evaluator.employees.index', compact('employees', 'search', 'statusFilter'));
    }

    public function show(Employee $employee): View
    {
        // Enforce policy authorization
        abort_if($employee->evaluator_id !== Auth::id(), 403, 'Anda tidak berhak melihat data tenaga kerja ini.');

        $employee->load(['evaluator', 'evaluation.answers.question']);

        return view('evaluator.employees.show', compact('employee'));
    }
}
