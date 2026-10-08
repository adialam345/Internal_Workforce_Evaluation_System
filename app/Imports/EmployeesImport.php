<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmployeesImport implements ToCollection, WithHeadingRow
{
    public array $errors = [];
    public int $importedCount = 0;
    public int $updatedCount = 0;
    public array $previewRows = [];

    protected bool $isDryRun = false;

    public function __construct(bool $isDryRun = false)
    {
        $this->isDryRun = $isDryRun;
    }

    public function collection(Collection $rows)
    {
        $evaluators = User::evaluators()->get()->keyBy(function ($item) {
            return strtolower(trim($item->email));
        });

        $evaluatorsByName = User::evaluators()->get()->keyBy(function ($item) {
            return strtolower(trim($item->name));
        });

        $seenNiksInFile = [];

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2; // Header is row 1, data starts at row 2

            $nik = trim((string)($row['nik'] ?? $row['employee_code'] ?? $row['kode_pegawai'] ?? ''));
            $nama = trim((string)($row['nama'] ?? $row['name'] ?? $row['nama_lengkap'] ?? ''));
            $jabatan = trim((string)($row['jabatan'] ?? $row['posisi'] ?? ''));
            $divisi = trim((string)($row['divisi'] ?? $row['division'] ?? ''));
            $department = trim((string)($row['department'] ?? $row['departemen'] ?? $divisi));
            $unit = trim((string)($row['unit'] ?? ''));
            $evaluatorInput = trim((string)($row['evaluator'] ?? $row['evaluator_email'] ?? $row['evaluator_nama'] ?? ''));

            // Basic required validation
            if (empty($nik)) {
                $this->errors[] = "Baris {$rowNumber}: NIK / Kode Tenaga Kerja wajib diisi.";
                continue;
            }

            if (empty($nama)) {
                $this->errors[] = "Baris {$rowNumber}: Nama Tenaga Kerja wajib diisi.";
                continue;
            }

            // Duplicate in current Excel check
            if (in_array($nik, $seenNiksInFile)) {
                $this->errors[] = "Baris {$rowNumber}: NIK '{$nik}' terduplikasi di dalam berkas Excel.";
                continue;
            }
            $seenNiksInFile[] = $nik;

            // Resolve evaluator
            $evaluatorId = null;
            if (!empty($evaluatorInput)) {
                $cleanInput = strtolower($evaluatorInput);
                if (isset($evaluators[$cleanInput])) {
                    $evaluatorId = $evaluators[$cleanInput]->id;
                } elseif (isset($evaluatorsByName[$cleanInput])) {
                    $evaluatorId = $evaluatorsByName[$cleanInput]->id;
                } elseif (is_numeric($evaluatorInput) && User::where('id', $evaluatorInput)->where('role', 'evaluator')->exists()) {
                    $evaluatorId = (int)$evaluatorInput;
                } else {
                    $this->errors[] = "Baris {$rowNumber}: Evaluator '{$evaluatorInput}' tidak ditemukan di database.";
                    continue;
                }
            }

            $this->previewRows[] = [
                'row' => $rowNumber,
                'nik' => $nik,
                'nama' => $nama,
                'jabatan' => $jabatan,
                'divisi' => $divisi,
                'department' => $department,
                'unit' => $unit,
                'evaluator_id' => $evaluatorId,
                'evaluator_name' => $evaluatorInput,
            ];

            if (!$this->isDryRun) {
                $employee = Employee::where('employee_code', $nik)->first();
                if ($employee) {
                    $employee->update([
                        'nama' => $nama,
                        'jabatan' => $jabatan ?: $employee->jabatan,
                        'divisi' => $divisi ?: $employee->divisi,
                        'department' => $department ?: $employee->department,
                        'unit' => $unit ?: $employee->unit,
                        'evaluator_id' => $evaluatorId ?: $employee->evaluator_id,
                    ]);
                    $this->updatedCount++;
                } else {
                    Employee::create([
                        'employee_code' => $nik,
                        'nama' => $nama,
                        'jabatan' => $jabatan,
                        'divisi' => $divisi,
                        'department' => $department,
                        'unit' => $unit,
                        'evaluator_id' => $evaluatorId,
                        'status' => 'active',
                    ]);
                    $this->importedCount++;
                }
            }
        }
    }
}
