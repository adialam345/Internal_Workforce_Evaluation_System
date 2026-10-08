<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EvaluationsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected ?int $evaluatorId;
    protected ?string $status;
    protected ?string $divisi;

    public function __construct(?int $evaluatorId = null, ?string $status = null, ?string $divisi = null)
    {
        $this->evaluatorId = $evaluatorId;
        $this->status = $status;
        $this->divisi = $divisi;
    }

    public function collection()
    {
        $query = Employee::with(['evaluator', 'evaluation.answers.question']);

        if ($this->evaluatorId) {
            $query->where('evaluator_id', $this->evaluatorId);
        }

        if ($this->divisi) {
            $query->where('divisi', $this->divisi);
        }

        if ($this->status) {
            if ($this->status === 'un-evaluated') {
                $query->whereDoesntHave('evaluation');
            } elseif ($this->status === 'draft') {
                $query->whereHas('evaluation', function ($q) {
                    $q->where('status', 'draft');
                });
            } elseif ($this->status === 'submitted') {
                $query->whereHas('evaluation', function ($q) {
                    $q->where('status', 'submitted');
                });
            }
        }

        return $query->orderBy('divisi')->orderBy('employee_code')->get();
    }

    public function headings(): array
    {
        return [
            'NIK',
            'Nama Tenaga Kerja',
            'Jabatan',
            'Divisi',
            'Departemen',
            'Unit',
            'Nama Evaluator',
            'Status Evaluasi',
            'Skor Akhir (Rata-rata)',
            'Tanggal Disubmit',
            'Kelebihan / Kekuatan',
            'Area Pengembangan',
            'Rekomendasi',
            'Catatan Umum Evaluator',
        ];
    }

    public function map($employee): array
    {
        $eval = $employee->evaluation;

        $statusText = 'Belum Dievaluasi';
        if ($eval) {
            $statusText = ($eval->status === 'submitted') ? 'Selesai (Submitted)' : 'Draft Sementara';
        }

        return [
            $employee->employee_code,
            $employee->nama,
            $employee->jabatan,
            $employee->divisi,
            $employee->department,
            $employee->unit,
            $employee->evaluator ? $employee->evaluator->name : 'Belum Ditugaskan',
            $statusText,
            $eval && $eval->total_score !== null ? number_format((float)$eval->total_score, 2) : '-',
            $eval && $eval->submitted_at ? $eval->submitted_at->format('d/m/Y H:i') : '-',
            $eval ? ($eval->strengths ?? '-') : '-',
            $eval ? ($eval->areas_for_improvement ?? '-') : '-',
            $eval ? ($eval->recommendations ?? '-') : '-',
            $eval ? ($eval->evaluator_notes ?? '-') : '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1E3A8A'],
                ],
            ],
        ];
    }
}
