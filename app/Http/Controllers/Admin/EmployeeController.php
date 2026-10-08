<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $divisi = $request->input('divisi');
        $evaluatorId = $request->input('evaluator_id');
        $status = $request->input('status');
        $evaluationStatus = $request->input('evaluation_status');

        $query = Employee::with(['evaluator', 'evaluation']);

        if ($search) {
            $query->search($search);
        }

        if ($divisi) {
            $query->filterDivisi($divisi);
        }

        if ($evaluatorId) {
            $query->filterEvaluator($evaluatorId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($evaluationStatus) {
            if ($evaluationStatus === 'submitted') {
                $query->whereHas('evaluation', function ($q) {
                    $q->where('status', 'submitted');
                });
            } elseif ($evaluationStatus === 'draft') {
                $query->whereHas('evaluation', function ($q) {
                    $q->where('status', 'draft');
                });
            } elseif ($evaluationStatus === 'none') {
                $query->whereDoesntHave('evaluation');
            }
        }

        $employees = $query->orderBy('nama')->paginate(25)->withQueryString();

        $divisions = Employee::select('divisi')
            ->whereNotNull('divisi')
            ->distinct()
            ->orderBy('divisi')
            ->pluck('divisi');

        $evaluators = User::evaluators()->active()->orderBy('name')->get();

        return view('admin.employees.index', compact(
            'employees',
            'divisions',
            'evaluators',
            'search',
            'divisi',
            'evaluatorId',
            'status',
            'evaluationStatus'
        ));
    }

    public function create(): View
    {
        $evaluators = User::evaluators()->active()->orderBy('name')->get();
        return view('admin.employees.create', compact('evaluators'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'employee_code' => ['required', 'string', 'max:50', 'unique:employees,employee_code'],
            'nama' => ['required', 'string', 'max:255'],
            'jabatan' => ['nullable', 'string', 'max:255'],
            'divisi' => ['nullable', 'string', 'max:255'],
            'department' => ['nullable', 'string', 'max:255'],
            'unit' => ['nullable', 'string', 'max:255'],
            'evaluator_id' => ['nullable', 'exists:users,id'],
            'status' => ['required', 'in:active,inactive'],
        ], [
            'employee_code.required' => 'NIK / Kode Tenaga Kerja wajib diisi.',
            'employee_code.unique' => 'NIK / Kode Tenaga Kerja sudah terdaftar.',
            'nama.required' => 'Nama lengkap wajib diisi.',
        ]);

        $employee = Employee::create($validated);

        AuditLog::record(
            'CREATE_EMPLOYEE',
            "Menambahkan tenaga kerja baru: {$employee->nama} ({$employee->employee_code})",
            'Employee',
            $employee->id,
            $validated
        );

        return redirect()->route('admin.employees.index')->with('success', "Tenaga kerja {$employee->nama} berhasil ditambahkan.");
    }

    public function edit(Employee $employee): View
    {
        $evaluators = User::evaluators()->active()->orderBy('name')->get();
        return view('admin.employees.edit', compact('employee', 'evaluators'));
    }

    public function update(Request $request, Employee $employee): RedirectResponse
    {
        $validated = $request->validate([
            'employee_code' => ['required', 'string', 'max:50', Rule::unique('employees')->ignore($employee->id)],
            'nama' => ['required', 'string', 'max:255'],
            'jabatan' => ['nullable', 'string', 'max:255'],
            'divisi' => ['nullable', 'string', 'max:255'],
            'department' => ['nullable', 'string', 'max:255'],
            'unit' => ['nullable', 'string', 'max:255'],
            'evaluator_id' => ['nullable', 'exists:users,id'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $employee->update($validated);

        AuditLog::record(
            'UPDATE_EMPLOYEE',
            "Memperbarui data tenaga kerja: {$employee->nama} ({$employee->employee_code})",
            'Employee',
            $employee->id,
            $validated
        );

        return redirect()->route('admin.employees.index')->with('success', "Data tenaga kerja {$employee->nama} berhasil diperbarui.");
    }

    public function destroy(Employee $employee): RedirectResponse
    {
        $name = $employee->nama;
        $code = $employee->employee_code;
        $id = $employee->id;

        $employee->delete();

        AuditLog::record(
            'DELETE_EMPLOYEE',
            "Menghapus data tenaga kerja: {$name} ({$code})",
            'Employee',
            $id
        );

        return redirect()->route('admin.employees.index')->with('success', "Tenaga kerja {$name} berhasil dihapus.");
    }

    public function bulkAssign(Request $request): RedirectResponse
    {
        $request->validate([
            'employee_ids' => ['required', 'array', 'min:1'],
            'employee_ids.*' => ['exists:employees,id'],
            'target_evaluator_id' => ['nullable'],
        ]);

        $evaluatorId = $request->input('target_evaluator_id');
        $evaluatorId = ($evaluatorId === '' || $evaluatorId === 'null') ? null : (int)$evaluatorId;

        $evaluatorName = 'Unassigned';
        if ($evaluatorId) {
            $evaluator = User::find($evaluatorId);
            $evaluatorName = $evaluator ? $evaluator->name : 'Unknown';
        }

        Employee::whereIn('id', $request->input('employee_ids'))->update([
            'evaluator_id' => $evaluatorId,
        ]);

        $count = count($request->input('employee_ids'));

        AuditLog::record(
            'BULK_ASSIGN_EMPLOYEES',
            "Melakukan penetapan massal {$count} tenaga kerja ke Evaluator: {$evaluatorName}",
            'User',
            $evaluatorId,
            ['count' => $count, 'employee_ids' => $request->input('employee_ids')]
        );

        return back()->with('success', "Berhasil memperbarui assignment {$count} tenaga kerja ke Evaluator: {$evaluatorName}.");
    }
}
