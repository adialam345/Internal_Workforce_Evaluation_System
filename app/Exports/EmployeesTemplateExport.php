<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EmployeesTemplateExport implements FromArray, WithHeadings, WithStyles
{
    public function headings(): array
    {
        return [
            'NIK',
            'Nama Lengkap',
            'Jabatan',
            'Divisi',
            'Departemen',
            'Unit',
            'Evaluator (Email atau Nama)',
        ];
    }

    public function array(): array
    {
        return [
            [
                'TK-0001',
                'Budi Pratama',
                'Operator Mesin',
                'Produksi',
                'Produksi',
                'Unit 1',
                'budi@workeval.local',
            ],
            [
                'TK-0002',
                'Siti Aminah',
                'Staff Gudang',
                'Logistik & Gudang',
                'Logistik & Gudang',
                'Unit 2',
                'siti@workeval.local',
            ],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1E40AF'],
                ],
            ],
        ];
    }
}
