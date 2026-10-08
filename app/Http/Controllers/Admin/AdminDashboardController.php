<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Evaluation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $totalEmployees = Employee::count();
        $assignedEmployees = Employee::whereNotNull('evaluator_id')->count();
        $unassignedEmployees = Employee::whereNull('evaluator_id')->count();

        $submittedEvaluations = Evaluation::where('status', 'submitted')->count();
        $draftEvaluations = Evaluation::where('status', 'draft')->count();
        $notStartedEmployees = $totalEmployees - ($submittedEvaluations + $draftEvaluations);
        if ($notStartedEmployees < 0) {
            $notStartedEmployees = 0;
        }

        $overallProgress = $totalEmployees > 0 
            ? round(($submittedEvaluations / $totalEmployees) * 100, 1) 
            : 0;

        $totalEvaluators = User::evaluators()->count();
        $activeEvaluators = User::evaluators()->active()->count();
        $inactiveEvaluators = $totalEvaluators - $activeEvaluators;

        // Progress per evaluator
        $evaluators = User::evaluators()
            ->withCount([
                'assignedEmployees as total_assigned',
                'evaluations as total_submitted' => function ($query) {
                    $query->where('status', 'submitted');
                },
                'evaluations as total_draft' => function ($query) {
                    $query->where('status', 'draft');
                },
            ])
            ->orderBy('name')
            ->get()
            ->map(function ($ev) {
                $total = $ev->total_assigned;
                $completed = $ev->total_submitted;
                $remaining = $total - $completed;
                $progress = $total > 0 ? round(($completed / $total) * 100, 1) : 0;

                $ev->remaining = $remaining > 0 ? $remaining : 0;
                $ev->progress = $progress;
                return $ev;
            });

        // Division stats for Chart.js
        $divisions = Employee::selectRaw('divisi, count(*) as count')
            ->whereNotNull('divisi')
            ->groupBy('divisi')
            ->orderByDesc('count')
            ->get();

        return view('admin.dashboard', compact(
            'totalEmployees',
            'assignedEmployees',
            'unassignedEmployees',
            'submittedEvaluations',
            'draftEvaluations',
            'notStartedEmployees',
            'overallProgress',
            'totalEvaluators',
            'activeEvaluators',
            'inactiveEvaluators',
            'evaluators',
            'divisions'
        ));
    }
}
