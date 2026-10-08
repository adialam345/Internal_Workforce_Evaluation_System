@extends('layouts.app')

@section('title', 'Hasil Evaluasi: ' . $evaluation->employee->nama)
@section('page_title', 'Hasil Evaluasi Selesai')
@section('page_subtitle', 'Lembar hasil penilaian kinerja tenaga kerja ' . $evaluation->employee->nama)

@section('content')
<div class="max-w-4xl space-y-6">

    <!-- Top Action Bar -->
    <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-xs flex items-center justify-between">
        <a href="{{ route('evaluator.employees.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-md border border-slate-300 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Daftar Anggota
        </a>

        <div class="flex items-center gap-2">
            @if($evaluation->isSubmitted())
                <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    Status: Selesai (Submitted)
                </span>
                <a href="{{ route('evaluations.print', $evaluation->id) }}" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-md shadow-xs transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Cetak Lembar Resmi
                </a>
            @else
                <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                    Status: Draft Sementara
                </span>
                <a href="{{ route('evaluator.evaluations.form', $evaluation->employee_id) }}" class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-md shadow-xs transition-colors">
                    Lanjutkan Pengisian Form
                </a>
            @endif
        </div>
    </div>

    <!-- Summary Card -->
    <div class="bg-white p-6 rounded-lg border border-slate-200 shadow-xs">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 text-xs">
            <div>
                <span class="text-slate-500 uppercase font-semibold text-[10px]">Tenaga Kerja</span>
                <div class="font-bold text-slate-900 text-sm mt-0.5">{{ $evaluation->employee->nama }}</div>
                <div class="font-mono text-slate-500">NIK: {{ $evaluation->employee->employee_code }}</div>
            </div>

            <div>
                <span class="text-slate-500 uppercase font-semibold text-[10px]">Jabatan & Divisi</span>
                <div class="font-bold text-slate-900 text-sm mt-0.5">{{ $evaluation->employee->jabatan ?? '-' }}</div>
                <div class="text-slate-500">{{ $evaluation->employee->divisi ?? '-' }} &bull; {{ $evaluation->employee->unit ?? '-' }}</div>
            </div>

            <div>
                <span class="text-slate-500 uppercase font-semibold text-[10px]">Evaluator Penilai</span>
                <div class="font-bold text-slate-900 text-sm mt-0.5">{{ $evaluation->evaluator->name }}</div>
                <div class="text-slate-500">{{ $evaluation->evaluator->department ?? '-' }}</div>
            </div>

            <div>
                <span class="text-slate-500 uppercase font-semibold text-[10px]">Skor Rata-Rata</span>
                <div class="text-2xl font-bold text-blue-700 mt-0.5">
                    {{ $evaluation->total_score !== null ? number_format((float)$evaluation->total_score, 2) : '-' }}
                    <span class="text-xs text-slate-400 font-normal">/ 5.00</span>
                </div>
                <div class="text-[11px] text-slate-500">
                    {{ $evaluation->submitted_at ? 'Disubmit: ' . $evaluation->submitted_at->format('d/m/Y H:i') : 'Draft' }}
                </div>
            </div>
        </div>
    </div>

    <!-- Criteria Breakdown Table -->
    <div class="bg-white rounded-lg border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-4 border-b border-slate-200">
            <h3 class="text-sm font-bold text-slate-900">Rincian Penilaian per Kriteria</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-semibold text-slate-600 uppercase tracking-wider">
                        <th class="py-3 px-4 w-12 text-center">No</th>
                        <th class="py-3 px-4">Kriteria & Indikator Penilaian</th>
                        <th class="py-3 px-4 text-center w-24">Skor (1-5)</th>
                        <th class="py-3 px-4">Catatan Evaluator</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse ($evaluation->answers as $idx => $ans)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-3.5 px-4 text-center font-semibold text-slate-500 text-xs">
                                {{ $idx + 1 }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="text-[10px] font-semibold text-blue-700 uppercase tracking-wider block">
                                    {{ $ans->question->category ?? 'Umum' }}
                                </span>
                                <div class="font-medium text-slate-900 mt-0.5">{{ $ans->question->question ?? '-' }}</div>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($ans->score !== null)
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full font-bold text-sm bg-blue-50 text-blue-700 border border-blue-200">
                                        {{ number_format((float)$ans->score, 0) }}
                                    </span>
                                @else
                                    <span class="text-xs text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-xs text-slate-700">
                                {{ $ans->notes ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-6 text-center text-slate-500 text-sm">
                                Belum ada rincian jawaban evaluasi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Qualitative Notes -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-xs">
            <h4 class="text-xs font-bold text-emerald-800 uppercase tracking-wider mb-2">Kelebihan / Kekuatan Kerja</h4>
            <p class="text-xs text-slate-700 whitespace-pre-line leading-relaxed">{{ $evaluation->strengths ?? 'Tidak ada catatan.' }}</p>
        </div>

        <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-xs">
            <h4 class="text-xs font-bold text-amber-800 uppercase tracking-wider mb-2">Area Pengembangan / Perbaikan</h4>
            <p class="text-xs text-slate-700 whitespace-pre-line leading-relaxed">{{ $evaluation->areas_for_improvement ?? 'Tidak ada catatan.' }}</p>
        </div>

        <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-xs">
            <h4 class="text-xs font-bold text-blue-800 uppercase tracking-wider mb-2">Rekomendasi Tindak Lanjut</h4>
            <p class="text-xs text-slate-700 whitespace-pre-line leading-relaxed">{{ $evaluation->recommendations ?? 'Tidak ada catatan.' }}</p>
        </div>

        <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-xs">
            <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-2">Catatan Umum Evaluator</h4>
            <p class="text-xs text-slate-700 whitespace-pre-line leading-relaxed">{{ $evaluation->evaluator_notes ?? 'Tidak ada catatan.' }}</p>
        </div>
    </div>

</div>
@endsection
