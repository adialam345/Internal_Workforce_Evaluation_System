<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\EvaluationQuestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EvaluationQuestionController extends Controller
{
    public function index(): View
    {
        $questions = EvaluationQuestion::withCount('answers')
            ->ordered()
            ->get();

        return view('admin.questions.index', compact('questions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category' => ['required', 'string', 'max:255'],
            'question' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'type' => ['required', 'in:rating,select,radio,text,number,textarea'],
            'min_score' => ['required', 'integer', 'min:1'],
            'max_score' => ['required', 'integer', 'gte:min_score'],
            'order' => ['required', 'integer', 'min:0'],
            'is_required' => ['required', 'boolean'],
            'is_active' => ['required', 'boolean'],
        ]);

        $q = EvaluationQuestion::create($validated);

        AuditLog::record(
            'CREATE_QUESTION',
            "Menambahkan kriteria evaluasi baru: {$q->category} - " . substr($q->question, 0, 50),
            'EvaluationQuestion',
            $q->id
        );

        return back()->with('success', 'Kriteria evaluasi baru berhasil ditambahkan.');
    }

    public function update(Request $request, EvaluationQuestion $question): RedirectResponse
    {
        $validated = $request->validate([
            'category' => ['required', 'string', 'max:255'],
            'question' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'type' => ['required', 'in:rating,select,radio,text,number,textarea'],
            'min_score' => ['required', 'integer', 'min:1'],
            'max_score' => ['required', 'integer', 'gte:min_score'],
            'order' => ['required', 'integer', 'min:0'],
            'is_required' => ['required', 'boolean'],
            'is_active' => ['required', 'boolean'],
        ]);

        $question->update($validated);

        AuditLog::record(
            'UPDATE_QUESTION',
            "Memperbarui kriteria evaluasi: {$question->category} - " . substr($question->question, 0, 50),
            'EvaluationQuestion',
            $question->id
        );

        return back()->with('success', 'Kriteria evaluasi berhasil diperbarui.');
    }

    public function toggleActive(EvaluationQuestion $question): RedirectResponse
    {
        $question->is_active = !$question->is_active;
        $question->save();

        $statusStr = $question->is_active ? 'diaktifkan' : 'dinonaktifkan';

        AuditLog::record(
            'TOGGLE_QUESTION',
            "Kriteria evaluasi ID #{$question->id} telah {$statusStr}.",
            'EvaluationQuestion',
            $question->id
        );

        return back()->with('success', "Kriteria evaluasi berhasil {$statusStr}.");
    }

    public function destroy(EvaluationQuestion $question): RedirectResponse
    {
        if ($question->answers()->exists()) {
            return back()->with('error', 'Kriteria ini sudah memiliki riwayat jawaban penilaian dan tidak dapat dihapus. Anda dapat menonaktifkannya.');
        }

        $id = $question->id;
        $cat = $question->category;
        $question->delete();

        AuditLog::record('DELETE_QUESTION', "Menghapus kriteria evaluasi #{$id} ({$cat})", 'EvaluationQuestion', $id);

        return back()->with('success', 'Kriteria evaluasi berhasil dihapus.');
    }
}
