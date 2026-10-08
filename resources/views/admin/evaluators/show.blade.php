@extends('layouts.app')

@section('title', 'Detail Evaluator: ' . $evaluator->name)
@section('page_title', 'Detail Evaluator: ' . $evaluator->name)
@section('page_subtitle', 'Pantau daftar tenaga kerja yang dialokasikan dan status penyelesaian evaluasi')

@section('content')
<div class="space-y-6">

    <!-- Profile & Metrics Header -->
    <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-xs">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-lg">
                    {{ strtoupper(substr($evaluator->name, 0, 2)) }}
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-base font-bold text-slate-900">{{ $evaluator->name }}</h2>
                        @if($evaluator->is_active)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Aktif</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">Nonaktif</span>
                        @endif
                    </div>
                    <p class="text-xs text-slate-500 mt-0.5 font-mono">{{ $evaluator->email }} &bull; {{ $evaluator->department ?? 'General' }} &bull; {{ $evaluator->phone ?? '-' }}</p>
                </div>
            </div>

            <div class="flex items-center gap-4 border-t md:border-t-0 pt-3 md:pt-0 border-slate-100">
                <div class="text-center px-3">
                    <div class="text-xs text-slate-500 uppercase font-semibold">Total Alokasi</div>
                    <div class="text-lg font-bold text-slate-900">{{ $totalAssigned }}</div>
                </div>
                <div class="text-center px-3 border-l border-slate-200">
                    <div class="text-xs text-slate-500 uppercase font-semibold">Submitted</div>
                    <div class="text-lg font-bold text-emerald-700">{{ $totalSubmitted }}</div>
                </div>
                <div class="text-center px-3 border-l border-slate-200">
                    <div class="text-xs text-slate-500 uppercase font-semibold">Progress</div>
                    <div class="text-lg font-bold text-blue-700">{{ $progress }}%</div>
                </div>
                <div class="pl-2 border-l border-slate-200">
                    <a href="{{ route('admin.evaluators.edit', $evaluator->id) }}" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-semibold rounded-md border border-slate-300 transition-colors">
                        Edit Akun
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Assigned Employees Table -->
    <div class="bg-white rounded-lg border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-4 border-b border-slate-200 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-900">Daftar Tenaga Kerja Tanggung Jawab {{ $evaluator->name }}</h3>
            <span class="text-xs text-slate-500">{{ $employees->total() }} Tenaga Kerja</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-semibold text-slate-600 uppercase tracking-wider">
                        <th class="py-3 px-4">NIK</th>
                        <th class="py-3 px-4">Nama Lengkap</th>
                        <th class="py-3 px-4">Jabatan & Divisi</th>
                        <th class="py-3 px-4 text-center">Status Evaluasi</th>
                        <th class="py-3 px-4 text-center">Skor Akhir</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse ($employees as $emp)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-3 px-4 font-mono text-xs font-bold text-slate-800">{{ $emp->employee_code }}</td>
                            <td class="py-3 px-4 font-semibold text-slate-900">{{ $emp->nama }}</td>
                            <td class="py-3 px-4 text-xs text-slate-600">{{ $emp->jabatan ?? '-' }} &bull; {{ $emp->divisi ?? '-' }}</td>
                            <td class="py-3 px-4 text-center">
                                @if($emp->evaluation)
                                    @if($emp->evaluation->isSubmitted())
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            Submitted
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                            Draft
                                        </span>
                                    @endif
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 text-slate-600 border border-slate-200">
                                        Belum Ada
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center font-bold text-slate-800 text-xs">
                                {{ $emp->evaluation && $emp->evaluation->total_score !== null ? number_format((float)$emp->evaluation->total_score, 2) : '-' }}
                            </td>
                            <td class="py-3 px-4 text-right">
                                @if($emp->evaluation && $emp->evaluation->isSubmitted())
                                    <a href="{{ route('evaluations.print', $emp->evaluation->id) }}" target="_blank" class="text-xs font-semibold text-blue-700 hover:text-blue-900">
                                        Cetak Hasil
                                    </a>
                                @else
                                    <span class="text-xs text-slate-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-500 text-sm">
                                Belum ada tenaga kerja yang ditugaskan ke evaluator ini.
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
