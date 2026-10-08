@extends('layouts.app')

@section('title', 'Dashboard Evaluator')
@section('page_title', 'Dashboard Evaluator')
@section('page_subtitle', 'Pantau dan selesaikan evaluasi tenaga kerja yang menjadi tanggung jawab Anda')

@section('content')
<div class="space-y-6">

    <!-- KPI Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1: Total Anggota -->
        <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Anggota Saya</span>
                <span class="p-2 bg-blue-50 text-blue-700 rounded-md">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </span>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-2xl font-bold text-slate-900">{{ number_format($totalAssigned) }}</span>
                <span class="text-xs text-slate-500">Tenaga Kerja</span>
            </div>
            <div class="mt-2 text-xs text-slate-500">
                Divisi: <strong>{{ auth()->user()->department ?? 'Operasional' }}</strong>
            </div>
        </div>

        <!-- Card 2: Selesai Evaluasi -->
        <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Selesai (Submitted)</span>
                <span class="p-2 bg-emerald-50 text-emerald-700 rounded-md">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-2xl font-bold text-emerald-700">{{ number_format($submittedCount) }}</span>
                <span class="text-xs text-slate-500">Berkas Selesai</span>
            </div>
            <div class="mt-2 text-xs text-slate-500">
                Draft: <strong class="text-amber-600">{{ number_format($draftCount) }}</strong> berkas
            </div>
        </div>

        <!-- Card 3: Belum Dievaluasi -->
        <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Belum Dievaluasi</span>
                <span class="p-2 bg-amber-50 text-amber-700 rounded-md">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-2xl font-bold text-slate-900">{{ number_format($unEvaluatedCount) }}</span>
                <span class="text-xs text-slate-500">Tenaga Kerja</span>
            </div>
            <div class="mt-2 text-xs text-slate-500">
                Menunggu penilaian Anda
            </div>
        </div>

        <!-- Card 4: Progress Penilaian -->
        <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Progress Saya</span>
                <span class="p-2 bg-indigo-50 text-indigo-700 rounded-md">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </span>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-2xl font-bold text-blue-700">{{ $progress }}%</span>
                <span class="text-xs text-slate-500">Tercapai</span>
            </div>
            <div class="mt-2 w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                <div class="bg-blue-600 h-2 rounded-full transition-all duration-500" style="width: {{ $progress }}%"></div>
            </div>
        </div>
    </div>

    <!-- Assigned Employees Table -->
    <div class="bg-white rounded-lg border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-4 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Daftar Tenaga Kerja Tanggung Jawab Saya</h3>
                <p class="text-xs text-slate-500 mt-0.5">Pilih tenaga kerja untuk memulai atau melanjutkan pengisian evaluasi</p>
            </div>

            <!-- Search & Filter Form -->
            <form method="GET" action="{{ route('evaluator.dashboard') }}" class="flex items-center gap-2">
                <input type="text" 
                       name="search" 
                       value="{{ $search }}" 
                       placeholder="Cari NIK / Nama..." 
                       class="px-2.5 py-1 text-xs bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600">
                
                <select name="status" class="px-2 py-1 text-xs bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600">
                    <option value="">Semua</option>
                    <option value="submitted" {{ $statusFilter === 'submitted' ? 'selected' : '' }}>Selesai</option>
                    <option value="draft" {{ $statusFilter === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="none" {{ $statusFilter === 'none' ? 'selected' : '' }}>Belum</option>
                </select>

                <button type="submit" class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-md shadow-xs">
                    Cari
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-semibold text-slate-600 uppercase tracking-wider">
                        <th class="py-3 px-4">NIK</th>
                        <th class="py-3 px-4">Nama Tenaga Kerja</th>
                        <th class="py-3 px-4">Jabatan & Divisi</th>
                        <th class="py-3 px-4 text-center">Status Evaluasi</th>
                        <th class="py-3 px-4 text-center">Skor Rata-rata</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse ($employees as $emp)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-3.5 px-4 font-mono text-xs font-bold text-slate-800">
                                {{ $emp->employee_code }}
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-semibold text-slate-900">{{ $emp->nama }}</div>
                                <div class="text-[11px] text-slate-500">{{ $emp->unit ?? '-' }}</div>
                            </td>
                            <td class="py-3.5 px-4 text-xs">
                                <div class="font-medium text-slate-800">{{ $emp->jabatan ?? '-' }}</div>
                                <div class="text-slate-500">{{ $emp->divisi ?? '-' }}</div>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($emp->evaluation)
                                    @if($emp->evaluation->isSubmitted())
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            Selesai (Submitted)
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                            Draft Tersimpan
                                        </span>
                                    @endif
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 text-slate-600 border border-slate-200">
                                        Belum Dievaluasi
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center font-bold text-xs text-slate-900">
                                {{ $emp->evaluation && $emp->evaluation->total_score !== null ? number_format((float)$emp->evaluation->total_score, 2) : '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                @if($emp->evaluation && $emp->evaluation->isSubmitted())
                                    <div class="inline-flex items-center gap-2">
                                        <a href="{{ route('evaluator.evaluations.show', $emp->evaluation->id) }}" class="inline-flex items-center gap-1 text-xs font-semibold text-slate-700 hover:text-slate-900">
                                            Lihat Hasil
                                        </a>
                                        <span class="text-slate-300">|</span>
                                        <a href="{{ route('evaluations.print', $emp->evaluation->id) }}" target="_blank" class="inline-flex items-center gap-1 text-xs font-semibold text-blue-700 hover:text-blue-900">
                                            Cetak
                                        </a>
                                    </div>
                                @else
                                    <a href="{{ route('evaluator.evaluations.form', $emp->id) }}" class="inline-flex items-center gap-1 px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-md shadow-xs transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        {{ $emp->evaluation ? 'Lanjutkan Evaluasi' : 'Mulai Evaluasi' }}
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-slate-500 text-sm">
                                <p class="font-semibold text-slate-700">Belum ada tenaga kerja yang ditugaskan kepada Anda.</p>
                                <p class="text-xs text-slate-400 mt-1">Silakan hubungi Administrator jika data belum muncul.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($employees->hasPages())
            <div class="p-4 border-t border-slate-200">
                {{ $employees->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
