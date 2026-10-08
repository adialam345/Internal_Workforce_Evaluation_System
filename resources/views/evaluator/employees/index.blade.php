@extends('layouts.app')

@section('title', 'Anggota Saya')
@section('page_title', 'Daftar Anggota Tenaga Kerja')
@section('page_subtitle', 'Kelola dan lakukan pengisian evaluasi terhadap tenaga kerja binaan Anda')

@section('content')
<div class="space-y-4">

    <!-- Filter Bar -->
    <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-xs flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <form method="GET" action="{{ route('evaluator.employees.index') }}" class="flex flex-wrap items-center gap-2 flex-1">
            <div class="w-full sm:w-64">
                <input type="text" 
                       name="search" 
                       value="{{ $search }}" 
                       placeholder="Cari NIK / Nama Tenaga Kerja..." 
                       class="w-full px-3 py-1.5 text-xs bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600">
            </div>

            <select name="status" class="px-2.5 py-1.5 text-xs bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600">
                <option value="">Semua Status Evaluasi</option>
                <option value="submitted" {{ $statusFilter === 'submitted' ? 'selected' : '' }}>Selesai (Submitted)</option>
                <option value="draft" {{ $statusFilter === 'draft' ? 'selected' : '' }}>Draft Sementara</option>
                <option value="none" {{ $statusFilter === 'none' ? 'selected' : '' }}>Belum Dievaluasi</option>
            </select>

            <button type="submit" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-md shadow-xs transition-colors">
                Filter
            </button>
            @if($search || $statusFilter)
                <a href="{{ route('evaluator.employees.index') }}" class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-medium rounded-md border border-slate-300">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Table Container -->
    <div class="bg-white rounded-lg border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-4 border-b border-slate-200">
            <span class="text-xs text-slate-600">Menampilkan <strong>{{ $employees->firstItem() ?? 0 }} - {{ $employees->lastItem() ?? 0 }}</strong> dari total <strong>{{ number_format($employees->total()) }}</strong> tenaga kerja binaan</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-semibold text-slate-600 uppercase tracking-wider">
                        <th class="py-3 px-4">NIK</th>
                        <th class="py-3 px-4">Nama Lengkap</th>
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
                                            Draft Sementara
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
                                        <a href="{{ route('evaluator.evaluations.show', $emp->evaluation->id) }}" class="text-xs font-semibold text-slate-700 hover:text-slate-900">
                                            Lihat Hasil
                                        </a>
                                        <span class="text-slate-300">|</span>
                                        <a href="{{ route('evaluations.print', $emp->evaluation->id) }}" target="_blank" class="text-xs font-semibold text-blue-700 hover:text-blue-900">
                                            Cetak Lembar
                                        </a>
                                    </div>
                                @else
                                    <a href="{{ route('evaluator.evaluations.form', $emp->id) }}" class="inline-flex items-center gap-1 px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-md shadow-xs transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        {{ $emp->evaluation ? 'Lanjutkan Evaluasi' : 'Isi Form Evaluasi' }}
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-slate-500 text-sm">
                                Tidak ada data tenaga kerja yang sesuai filter.
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
