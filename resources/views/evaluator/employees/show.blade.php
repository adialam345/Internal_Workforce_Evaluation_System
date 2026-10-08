@extends('layouts.app')

@section('title', 'Detail Anggota: ' . $employee->nama)
@section('page_title', 'Detail Tenaga Kerja')
@section('page_subtitle', 'Informasi profil dan riwayat evaluasi tenaga kerja')

@section('content')
<div class="max-w-3xl space-y-6">

    <!-- Top Action Bar -->
    <div class="flex items-center justify-between">
        <a href="{{ route('evaluator.employees.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-md border border-slate-300 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Daftar
        </a>

        @if(!$employee->evaluation || !$employee->evaluation->isSubmitted())
            <a href="{{ route('evaluator.evaluations.form', $employee->id) }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-md shadow-xs transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                {{ $employee->evaluation ? 'Lanjutkan Form Evaluasi' : 'Mulai Evaluasi Sekarang' }}
            </a>
        @else
            <a href="{{ route('evaluations.print', $employee->evaluation->id) }}" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold rounded-md shadow-xs transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak Lembar Evaluasi
            </a>
        @endif
    </div>

    <!-- Profile Card -->
    <div class="bg-white p-6 rounded-lg border border-slate-200 shadow-xs space-y-4">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-lg bg-blue-100 text-blue-800 flex items-center justify-center font-bold text-xl">
                {{ strtoupper(substr($employee->nama, 0, 2)) }}
            </div>
            <div>
                <h3 class="text-lg font-bold text-slate-900">{{ $employee->nama }}</h3>
                <p class="font-mono text-xs text-slate-500 font-semibold">NIK: {{ $employee->employee_code }}</p>
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 pt-4 border-t border-slate-100 text-xs">
            <div>
                <span class="text-slate-500 uppercase font-semibold text-[10px]">Jabatan</span>
                <p class="font-bold text-slate-900 mt-0.5">{{ $employee->jabatan ?? '-' }}</p>
            </div>
            <div>
                <span class="text-slate-500 uppercase font-semibold text-[10px]">Divisi</span>
                <p class="font-bold text-slate-900 mt-0.5">{{ $employee->divisi ?? '-' }}</p>
            </div>
            <div>
                <span class="text-slate-500 uppercase font-semibold text-[10px]">Departemen</span>
                <p class="font-bold text-slate-900 mt-0.5">{{ $employee->department ?? '-' }}</p>
            </div>
            <div>
                <span class="text-slate-500 uppercase font-semibold text-[10px]">Unit / Shift</span>
                <p class="font-bold text-slate-900 mt-0.5">{{ $employee->unit ?? '-' }}</p>
            </div>
            <div>
                <span class="text-slate-500 uppercase font-semibold text-[10px]">Status Tenaga Kerja</span>
                <p class="font-bold text-slate-900 mt-0.5 uppercase">{{ $employee->status }}</p>
            </div>
            <div>
                <span class="text-slate-500 uppercase font-semibold text-[10px]">Status Evaluasi</span>
                <p class="font-bold mt-0.5">
                    @if($employee->evaluation)
                        <span class="{{ $employee->evaluation->isSubmitted() ? 'text-emerald-700' : 'text-amber-700' }}">
                            {{ $employee->evaluation->isSubmitted() ? 'Submitted' : 'Draft' }}
                        </span>
                    @else
                        <span class="text-slate-400">Belum Dievaluasi</span>
                    @endif
                </p>
            </div>
        </div>
    </div>

</div>
@endsection
