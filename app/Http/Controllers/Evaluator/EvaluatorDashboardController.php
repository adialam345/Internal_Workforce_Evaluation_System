<?php

namespace App\Http\Controllers\Evaluator;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Evaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class EvaluatorDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $evaluatorId = Auth::id();

        $totalAssigned = Employee::assignedTo($evaluatorId)->count();
        $submittedCount = Evaluation::where('evaluator_id', $evaluatorId)->where('status', 'submitted')->count();
        $draftCount = Evaluation::where('evaluator_id', $evaluatorId)->where('status', 'draft')->count();
        $unEvaluatedCount = $totalAssigned - ($submittedCount + $draftCount);
        if ($unEvaluatedCount < 0) {
            $unEvaluatedCount = 0;
        }

        $progress = $totalAssigned > 0 
            ? round(($submittedCount / $totalAssigned) * 100, 1) 
            : 0;

        // Assigned employees preview with search
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

        $employees = $query->orderBy('nama')->paginate(15)->withQueryString();

        return view('evaluator.dashboard', compact(
            'totalAssigned',
            'submittedCount',
            'draftCount',
            'unEvaluatedCount',
            'progress',
            'employees',
            'search',
            'statusFilter'
        ));
    }
}
