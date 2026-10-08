<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Evaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PrintController extends Controller
{
    public function printEvaluation(Evaluation $evaluation): View
    {
        $user = Auth::user();

        // Authorization check: Admin or the assigned evaluator
        abort_if(!$user->isAdmin() && $evaluation->evaluator_id !== $user->id, 403, 'Akses cetak evaluasi ditolak.');

        $evaluation->load(['employee.evaluator', 'evaluator', 'answers.question']);

        AuditLog::record(
            'PRINT_EVALUATION',
            "Mencetak lembar hasil evaluasi untuk tenaga kerja {$evaluation->employee->nama} ({$evaluation->employee->employee_code}).",
            'Evaluation',
            $evaluation->id
        );

        return view('evaluations.print', compact('evaluation'));
    }
}
