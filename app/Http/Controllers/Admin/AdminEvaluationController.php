<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Employee;
use App\Models\Evaluation;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminEvaluationController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $evaluatorId = $request->input('evaluator_id');
        $divisi = $request->input('divisi');

        $query = Evaluation::with(['employee', 'evaluator']);

        if ($search) {
            $query->whereHas('employee', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('employee_code', 'like', "%{$search}%")
                  ->orWhere('jabatan', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($evaluatorId) {
            $query->where('evaluator_id', $evaluatorId);
        }

        if ($divisi) {
            $query->whereHas('employee', function ($q) use ($divisi) {
                $q->where('divisi', $divisi);
            });
        }

        $evaluations = $query->orderByDesc('updated_at')->paginate(25)->withQueryString();

        $evaluators = User::evaluators()->orderBy('name')->get();
        $divisions = Employee::select('divisi')->whereNotNull('divisi')->distinct()->pluck('divisi');

        return view('admin.evaluations.index', compact(
            'evaluations',
            'evaluators',
            'divisions',
            'search',
            'status',
            'evaluatorId',
            'divisi'
        ));
    }

    public function show(Evaluation $evaluation): View
    {
        $evaluation->load(['employee', 'evaluator', 'unlockedByUser', 'answers.question']);

        return view('admin.evaluations.show', compact('evaluation'));
    }

    public function unlock(Request $request, Evaluation $evaluation): RedirectResponse
    {
        $reason = $request->input('reason', 'Permintaan revisi oleh Administrator');

        $previousStatus = $evaluation->status;
        $evaluation->status = 'draft';
        $evaluation->unlocked_by = Auth::id();
        $evaluation->unlocked_at = now();
        $evaluation->save();

        AuditLog::record(
            'UNLOCK_EVALUATION',
            "Membuka kembali evaluasi tenaga kerja {$evaluation->employee->nama} ({$evaluation->employee->employee_code}) untuk revisi Evaluator. Alasan: {$reason}",
            'Evaluation',
            $evaluation->id,
            ['reason' => $reason, 'previous_status' => $previousStatus]
        );

        return back()->with('success', "Evaluasi untuk {$evaluation->employee->nama} berhasil dibuka kembali (status diubah menjadi Draft). Evaluator dapat melakukan perubahan.");
    }
}
