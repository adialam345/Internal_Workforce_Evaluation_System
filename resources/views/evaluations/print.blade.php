<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lembar Evaluasi - {{ $evaluation->employee->employee_code }} - {{ $evaluation->employee->nama }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: #ffffff !important; color: #000000 !important; font-size: 10pt; }
            .print-page { padding: 0 !important; margin: 0 !important; max-width: 100% !important; box-shadow: none !important; border: none !important; }
        }
    </style>
</head>
<body class="bg-slate-100 antialiased text-slate-900 min-h-screen py-6 px-4">

    <!-- Print Action Bar (Hidden on Print) -->
    <div class="max-w-4xl mx-auto mb-4 no-print flex items-center justify-between bg-white p-4 rounded-lg border border-slate-200 shadow-xs">
        <div class="flex items-center gap-2">
            <button onclick="window.history.back()" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-md border border-slate-300">
                &larr; Kembali
            </button>
            <span class="text-xs text-slate-500">Pratinjau Lembar Berita Acara Evaluasi</span>
        </div>
        <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-md shadow-xs">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Cetak Dokumen (Ctrl + P)
        </button>
    </div>

    <!-- Printable Sheet Container -->
    <div class="print-page max-w-4xl mx-auto bg-white p-8 sm:p-12 rounded-lg border border-slate-200 shadow-sm space-y-6">

        <!-- Official Header / Kop Surat -->
        <div class="border-b-2 border-slate-900 pb-4 flex items-start justify-between">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-md bg-slate-900 text-white font-bold text-xl flex items-center justify-center">
                    WE
                </div>
                <div>
                    <h1 class="text-base font-bold uppercase tracking-wider text-slate-900">PT. WORKFORCE EVALUATION SYSTEM</h1>
                    <p class="text-xs text-slate-600">Sistem Informasi Manajemen Evaluasi & Kinerja Tenaga Kerja</p>
                    <p class="text-[11px] text-slate-500">Dokumen Resmi Internal Perusahaan &bull; Bersifat Rahasia</p>
                </div>
            </div>
            <div class="text-right text-xs">
                <div class="font-bold text-slate-800">LEMBAR PENILAIAN KERJA</div>
                <div class="font-mono text-[11px] text-slate-500 mt-0.5">DOC-EVAL-{{ str_pad($evaluation->id, 5, '0', STR_PAD_LEFT) }}</div>
            </div>
        </div>

        <!-- Employee & Evaluation Meta Info Table -->
        <div class="grid grid-cols-2 gap-x-8 gap-y-2 text-xs border border-slate-300 p-4 rounded-sm bg-slate-50/50">
            <div class="flex">
                <span class="w-32 font-semibold text-slate-600">NIK:</span>
                <span class="font-mono font-bold text-slate-900">{{ $evaluation->employee->employee_code }}</span>
            </div>
            <div class="flex">
                <span class="w-32 font-semibold text-slate-600">Evaluator:</span>
                <span class="font-semibold text-slate-900">{{ $evaluation->evaluator->name }}</span>
            </div>
            <div class="flex">
                <span class="w-32 font-semibold text-slate-600">Nama Lengkap:</span>
                <span class="font-bold text-slate-900">{{ $evaluation->employee->nama }}</span>
            </div>
            <div class="flex">
                <span class="w-32 font-semibold text-slate-600">Departemen / Divisi:</span>
                <span class="text-slate-900">{{ $evaluation->employee->divisi ?? '-' }}</span>
            </div>
            <div class="flex">
                <span class="w-32 font-semibold text-slate-600">Jabatan / Posisi:</span>
                <span class="text-slate-900">{{ $evaluation->employee->jabatan ?? '-' }}</span>
            </div>
            <div class="flex">
                <span class="w-32 font-semibold text-slate-600">Tanggal Evaluasi:</span>
                <span class="text-slate-900">{{ $evaluation->submitted_at ? $evaluation->submitted_at->format('d F Y') : date('d F Y') }}</span>
            </div>
            <div class="flex">
                <span class="w-32 font-semibold text-slate-600">Unit / Shift:</span>
                <span class="text-slate-900">{{ $evaluation->employee->unit ?? '-' }}</span>
            </div>
            <div class="flex">
                <span class="w-32 font-semibold text-slate-600">Status Berkas:</span>
                <span class="font-semibold uppercase text-emerald-800">{{ $evaluation->status }}</span>
            </div>
        </div>

        <!-- Detailed Criteria Breakdown Table -->
        <div>
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900 mb-2">I. Hasil Penilaian Kriteria & Indikator Kerja</h3>
            <table class="w-full border-collapse border border-slate-300 text-xs">
                <thead>
                    <tr class="bg-slate-100 border-b border-slate-300 font-semibold text-slate-800">
                        <th class="border border-slate-300 py-2 px-2 w-10 text-center">No</th>
                        <th class="border border-slate-300 py-2 px-3 text-left">Kategori & Aspek yang Dinilai</th>
                        <th class="border border-slate-300 py-2 px-3 w-20 text-center">Skor (1-5)</th>
                        <th class="border border-slate-300 py-2 px-3 text-left">Catatan Observasi Evaluator</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($evaluation->answers as $idx => $ans)
                        <tr class="border-b border-slate-200">
                            <td class="border border-slate-300 py-2 px-2 text-center font-medium text-slate-700">{{ $idx + 1 }}</td>
                            <td class="border border-slate-300 py-2 px-3">
                                <strong class="text-slate-900 block">{{ $ans->question->category ?? 'Umum' }}:</strong>
                                <span class="text-slate-700">{{ $ans->question->question ?? '-' }}</span>
                            </td>
                            <td class="border border-slate-300 py-2 px-3 text-center font-bold text-slate-900 text-sm">
                                {{ $ans->score !== null ? number_format((float)$ans->score, 0) : '-' }}
                            </td>
                            <td class="border border-slate-300 py-2 px-3 text-slate-700">
                                {{ $ans->notes ?? $ans->answer ?? '-' }}
                            </td>
                        </tr>
                    @endforeach
                    <!-- Total Average Row -->
                    <tr class="bg-slate-50 font-bold">
                        <td colspan="2" class="border border-slate-300 py-2.5 px-3 text-right uppercase">
                            Rata-Rata Skor Akhir (Skala 1 - 5):
                        </td>
                        <td class="border border-slate-300 py-2.5 px-3 text-center text-sm text-blue-900">
                            {{ $evaluation->total_score !== null ? number_format((float)$evaluation->total_score, 2) : '-' }}
                        </td>
                        <td class="border border-slate-300 py-2.5 px-3 text-slate-800 font-semibold">
                            @php
                                $score = (float)($evaluation->total_score ?? 0);
                                if ($score >= 4.5) $predikat = 'Sangat Baik (A)';
                                elseif ($score >= 3.5) $predikat = 'Baik (B)';
                                elseif ($score >= 2.5) $predikat = 'Cukup (C)';
                                else $predikat = 'Kurang (D)';
                            @endphp
                            Predikat: {{ $predikat }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Qualitative Notes Section -->
        <div class="space-y-3">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900 mb-2">II. Catatan Kualitatif & Rekomendasi</h3>
            
            <div class="border border-slate-300 p-3 rounded-sm text-xs space-y-2">
                <div>
                    <strong class="text-slate-800 block">Kelebihan / Kekuatan Kerja:</strong>
                    <p class="text-slate-700 mt-0.5 whitespace-pre-line">{{ $evaluation->strengths ?? '-' }}</p>
                </div>

                <div class="pt-2 border-t border-slate-200">
                    <strong class="text-slate-800 block">Area Pengembangan / Perbaikan:</strong>
                    <p class="text-slate-700 mt-0.5 whitespace-pre-line">{{ $evaluation->areas_for_improvement ?? '-' }}</p>
                </div>

                <div class="pt-2 border-t border-slate-200">
                    <strong class="text-slate-800 block">Rekomendasi Tindak Lanjut:</strong>
                    <p class="text-slate-700 mt-0.5 whitespace-pre-line">{{ $evaluation->recommendations ?? '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Signatures Area -->
        <div class="pt-6">
            <div class="grid grid-cols-3 gap-6 text-xs text-center">
                <!-- Tenaga Kerja -->
                <div class="space-y-16">
                    <p class="text-slate-600">Tenaga Kerja yang Dinilai,</p>
                    <div>
                        <div class="border-b border-slate-900 w-3/4 mx-auto font-bold text-slate-900">
                            {{ $evaluation->employee->nama }}
                        </div>
                        <p class="text-[10px] text-slate-500 mt-1">NIK: {{ $evaluation->employee->employee_code }}</p>
                    </div>
                </div>

                <!-- Evaluator -->
                <div class="space-y-16">
                    <p class="text-slate-600">Evaluator / Penilai,</p>
                    <div>
                        <div class="border-b border-slate-900 w-3/4 mx-auto font-bold text-slate-900">
                            {{ $evaluation->evaluator->name }}
                        </div>
                        <p class="text-[10px] text-slate-500 mt-1">{{ $evaluation->evaluator->department ?? 'Evaluator' }}</p>
                    </div>
                </div>

                <!-- Mengetahui Atasan / HR Admin -->
                <div class="space-y-16">
                    <p class="text-slate-600">Mengetahui (HR / Manajemen),</p>
                    <div>
                        <div class="border-b border-slate-900 w-3/4 mx-auto font-bold text-slate-900">
                            ( ............................................ )
                        </div>
                        <p class="text-[10px] text-slate-500 mt-1">HR & Operations Dept</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

</body>
</html>
