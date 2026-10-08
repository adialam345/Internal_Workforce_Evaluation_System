<?php

namespace App\Http\Controllers\Admin;

use App\Exports\EmployeesTemplateExport;
use App\Exports\EvaluationsExport;
use App\Http\Controllers\Controller;
use App\Imports\EmployeesImport;
use App\Models\AuditLog;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ImportExportController extends Controller
{
    public function showImport(): View
    {
        $evaluators = User::evaluators()->active()->orderBy('name')->get();
        return view('admin.import.index', compact('evaluators'));
    }

    public function downloadTemplate(): BinaryFileResponse
    {
        return Excel::download(new EmployeesTemplateExport, 'template_import_tenaga_kerja.xlsx');
    }

    public function processImport(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:10240'],
        ], [
            'file.required' => 'Silakan pilih berkas Excel / CSV terlebih dahulu.',
            'file.mimes' => 'Format berkas harus berupa .xlsx, .xls, atau .csv.',
            'file.max' => 'Ukuran berkas maksimal adalah 10MB.',
        ]);

        $import = new EmployeesImport(isDryRun: false);

        try {
            Excel::import($import, $request->file('file'));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses berkas Excel: ' . $e->getMessage());
        }

        if (!empty($import->errors)) {
            return back()
                ->with('import_errors', $import->errors)
                ->with('warning', "Import selesai dengan beberapa catatan: {$import->importedCount} data baru ditambahkan, {$import->updatedCount} data diperbarui, namun terdapat " . count($import->errors) . " baris yang gagal diproses.");
        }

        AuditLog::record(
            'IMPORT_EMPLOYEES',
            "Melakukan import Excel: {$import->importedCount} data baru, {$import->updatedCount} data diperbarui.",
            'Employee',
            null,
            ['imported' => $import->importedCount, 'updated' => $import->updatedCount]
        );

        return redirect()->route('admin.employees.index')->with('success', "Proses import berhasil! {$import->importedCount} data baru berhasil ditambahkan, dan {$import->updatedCount} data diperbarui.");
    }

    public function showExport(): View
    {
        $evaluators = User::evaluators()->active()->orderBy('name')->get();
        $divisions = Employee::select('divisi')->whereNotNull('divisi')->distinct()->orderBy('divisi')->pluck('divisi');

        return view('admin.export.index', compact('evaluators', 'divisions'));
    }

    public function processExport(Request $request): BinaryFileResponse
    {
        $evaluatorId = $request->input('evaluator_id') ? (int)$request->input('evaluator_id') : null;
        $status = $request->input('status');
        $divisi = $request->input('divisi');

        $filename = 'evaluasi_tenaga_kerja_' . date('Y-m-d_His') . '.xlsx';

        AuditLog::record(
            'EXPORT_DATA',
            "Mengekspor laporan evaluasi ke Excel dengan filter: Evaluator=" . ($evaluatorId ?: 'Semua') . ", Status=" . ($status ?: 'Semua') . ", Divisi=" . ($divisi ?: 'Semua')
        );

        return Excel::download(new EvaluationsExport($evaluatorId, $status, $divisi), $filename);
    }
}
