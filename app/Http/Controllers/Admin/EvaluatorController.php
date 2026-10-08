<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EvaluatorController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $department = $request->input('department');
        $status = $request->input('status');

        $query = User::evaluators()->withCount([
            'assignedEmployees as total_assigned',
            'evaluations as total_submitted' => function ($q) {
                $q->where('status', 'submitted');
            },
            'evaluations as total_draft' => function ($q) {
                $q->where('status', 'draft');
            },
        ]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($department) {
            $query->where('department', $department);
        }

        if ($status !== null && $status !== '') {
            $query->where('is_active', $status === 'active');
        }

        $evaluators = $query->orderBy('name')->paginate(20)->withQueryString();

        $departments = User::evaluators()
            ->whereNotNull('department')
            ->distinct()
            ->orderBy('department')
            ->pluck('department');

        return view('admin.evaluators.index', compact(
            'evaluators',
            'departments',
            'search',
            'department',
            'status'
        ));
    }

    public function create(): View
    {
        return view('admin.evaluators.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'department' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'is_active' => ['required', 'boolean'],
        ]);

        $evaluator = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'evaluator',
            'department' => $validated['department'],
            'phone' => $validated['phone'],
            'is_active' => $validated['is_active'],
        ]);

        AuditLog::record(
            'CREATE_EVALUATOR',
            "Membuat akun evaluator baru: {$evaluator->name} ({$evaluator->email})",
            'User',
            $evaluator->id
        );

        return redirect()->route('admin.evaluators.index')->with('success', "Akun Evaluator {$evaluator->name} berhasil dibuat.");
    }

    public function show(User $evaluator): View
    {
        abort_if(!$evaluator->isEvaluator(), 404);

        $employees = $evaluator->assignedEmployees()
            ->with('evaluation')
            ->orderBy('nama')
            ->paginate(20);

        $totalAssigned = $evaluator->assignedEmployees()->count();
        $totalSubmitted = $evaluator->evaluations()->where('status', 'submitted')->count();
        $totalDraft = $evaluator->evaluations()->where('status', 'draft')->count();
        $progress = $totalAssigned > 0 ? round(($totalSubmitted / $totalAssigned) * 100, 1) : 0;

        return view('admin.evaluators.show', compact(
            'evaluator',
            'employees',
            'totalAssigned',
            'totalSubmitted',
            'totalDraft',
            'progress'
        ));
    }

    public function edit(User $evaluator): View
    {
        abort_if(!$evaluator->isEvaluator(), 404);
        return view('admin.evaluators.edit', compact('evaluator'));
    }

    public function update(Request $request, User $evaluator): RedirectResponse
    {
        abort_if(!$evaluator->isEvaluator(), 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($evaluator->id)],
            'password' => ['nullable', 'string', 'min:6'],
            'department' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'is_active' => ['required', 'boolean'],
        ]);

        $evaluator->name = $validated['name'];
        $evaluator->email = $validated['email'];
        $evaluator->department = $validated['department'];
        $evaluator->phone = $validated['phone'];
        $evaluator->is_active = $validated['is_active'];

        if (!empty($validated['password'])) {
            $evaluator->password = Hash::make($validated['password']);
        }

        $evaluator->save();

        AuditLog::record(
            'UPDATE_EVALUATOR',
            "Memperbarui data akun evaluator: {$evaluator->name} ({$evaluator->email})",
            'User',
            $evaluator->id
        );

        return redirect()->route('admin.evaluators.index')->with('success', "Data Evaluator {$evaluator->name} berhasil diperbarui.");
    }

    public function toggleStatus(User $evaluator): RedirectResponse
    {
        abort_if(!$evaluator->isEvaluator(), 404);

        $evaluator->is_active = !$evaluator->is_active;
        $evaluator->save();

        $statusStr = $evaluator->is_active ? 'diaktifkan' : 'dinonaktifkan';

        AuditLog::record(
            'TOGGLE_EVALUATOR_STATUS',
            "Akun evaluator {$evaluator->name} telah {$statusStr}.",
            'User',
            $evaluator->id
        );

        return back()->with('success', "Status akun Evaluator {$evaluator->name} berhasil diubah menjadi {$statusStr}.");
    }
}
