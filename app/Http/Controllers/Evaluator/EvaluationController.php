<?php

namespace App\Http\Controllers\Evaluator;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Employee;
use App\Models\Evaluation;
use App\Models\EvaluationAnswer;
use App\Models\EvaluationQuestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class EvaluationController extends Controller
{
    public function form(Employee $employee): View|RedirectResponse
    {
        abort_if($employee->evaluator_id !== Auth::id(), 403, 'Tenaga kerja ini tidak ditugaskan kepada Anda.');

        $evaluation = Evaluation::with('answers')->where('employee_id', $employee->id)->first();

        if ($evaluation && $evaluation->isSubmitted()) {
            return redirect()->route('evaluator.evaluations.show', $evaluation->id)
                ->with('info', 'Evaluasi untuk tenaga kerja ini telah disubmit dan berstatus selesai (Read-Only).');
        }

        $questions = EvaluationQuestion::active()->ordered()->get();

        $existingAnswers = [];
        if ($evaluation) {
            foreach ($evaluation->answers as $ans) {
                $existingAnswers[$ans->question_id] = [
                    'score' => $ans->score,
                    'answer' => $ans->answer,
                    'notes' => $ans->notes,
                ];
            }
        }

        return view('evaluator.evaluations.form', compact('employee', 'evaluation', 'questions', 'existingAnswers'));
    }

    public function save(Request $request, Employee $employee): RedirectResponse
    {
        abort_if($employee->evaluator_id !== Auth::id(), 403, 'Tenaga kerja ini tidak ditugaskan kepada Anda.');

        $evaluation = Evaluation::where('employee_id', $employee->id)->first();
        if ($evaluation && $evaluation->isSubmitted()) {
            return redirect()->route('evaluator.evaluations.show', $evaluation->id)
                ->with('error', 'Evaluasi telah disubmit dan tidak dapat diubah kembali kecuali dibuka oleh Administrator.');
        }

        $action = $request->input('action'); // 'draft' or 'submit'
        $questions = EvaluationQuestion::active()->ordered()->get();

        // Validation rules if submitting
        if ($action === 'submit') {
            $rules = [
                'scores' => ['required', 'array'],
                'strengths' => ['required', 'string', 'min:5'],
                'areas_for_improvement' => ['required', 'string', 'min:5'],
                'recommendations' => ['nullable', 'string'],
                'evaluator_notes' => ['nullable', 'string'],
            ];

            foreach ($questions as $q) {
                if ($q->is_required) {
                    $rules["scores.{$q->id}"] = ['required', 'numeric', "min:{$q->min_score}", "max:{$q->max_score}"];
                }
            }

            $messages = [
                'scores.*.required' => 'Setiap indikator penilaian wajib diisi skornya sebelum submit.',
                'strengths.required' => 'Kelebihan / catatan kekuatan wajib diisi sebelum submit.',
                'areas_for_improvement.required' => 'Area perbaikan / pengembangan wajib diisi sebelum submit.',
            ];

            $request->validate($rules, $messages);
        }

        DB::transaction(function () use ($request, $employee, $action, $questions) {
            $scores = $request->input('scores', []);
            $notes = $request->input('notes', []);
            $textAnswers = $request->input('answers', []);

            $evalData = [
                'employee_id' => $employee->id,
                'evaluator_id' => Auth::id(),
                'status' => ($action === 'submit') ? 'submitted' : 'draft',
                'strengths' => $request->input('strengths'),
                'areas_for_improvement' => $request->input('areas_for_improvement'),
                'recommendations' => $request->input('recommendations'),
                'evaluator_notes' => $request->input('evaluator_notes'),
            ];

            if ($action === 'submit') {
                $evalData['submitted_at'] = now();
            }

            $evaluation = Evaluation::updateOrCreate(
                ['employee_id' => $employee->id],
                $evalData
            );

            // Save individual answers
            foreach ($questions as $q) {
                $scoreVal = isset($scores[$q->id]) && $scores[$q->id] !== '' ? (float)$scores[$q->id] : null;
                $noteVal = $notes[$q->id] ?? null;
                $ansVal = $textAnswers[$q->id] ?? ($scoreVal !== null ? "Skor: {$scoreVal}" : null);

                EvaluationAnswer::updateOrCreate(
                    [
                        'evaluation_id' => $evaluation->id,
                        'question_id' => $q->id,
                    ],
                    [
                        'score' => $scoreVal,
                        'answer' => $ansVal,
                        'notes' => $noteVal,
                    ]
                );
            }

            $evaluation->recalculateTotalScore();

            AuditLog::record(
                ($action === 'submit') ? 'SUBMIT_EVALUATION' : 'SAVE_DRAFT_EVALUATION',
                ($action === 'submit') 
                    ? "Menyelesaikan dan mensubmit evaluasi tenaga kerja: {$employee->nama} ({$employee->employee_code}) dengan skor rata-rata {$evaluation->total_score}."
                    : "Menyimpan draft evaluasi sementara untuk tenaga kerja: {$employee->nama} ({$employee->employee_code}).",
                'Evaluation',
                $evaluation->id
            );
        });

        if ($action === 'submit') {
            return redirect()->route('evaluator.employees.index')->with('success', "Evaluasi untuk {$employee->nama} berhasil disubmit secara resmi.");
        }

        return redirect()->route('evaluator.employees.index')->with('success', "Draft evaluasi untuk {$employee->nama} berhasil disimpan.");
    }

    public function show(Evaluation $evaluation): View
    {
        // Enforce authorization
        abort_if(!Auth::user()->isAdmin() && $evaluation->evaluator_id !== Auth::id(), 403, 'Akses evaluasi ditolak.');

        $evaluation->load(['employee', 'evaluator', 'answers.question']);

        return view('evaluator.evaluations.show', compact('evaluation'));
    }
}
