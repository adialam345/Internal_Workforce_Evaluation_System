@extends('layouts.app')

@section('title', 'Hasil Evaluasi')
@section('page_title', 'Hasil & Monitoring Evaluasi')
@section('page_subtitle', 'Pantau seluruh lembar evaluasi tenaga kerja, nilai rata-rata, dan otorisasi unlock evaluasi')

@section('content')
<div class="space-y-4">

    <!-- Search & Filter Bar -->
    <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-xs">
        <form method="GET" action="{{ route('admin.evaluations.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <div class="lg:col-span-2">
                <label class="block text-xs font-semibold text-slate-700 mb-1">Cari NIK / Nama Tenaga Kerja</label>
                <input type="text" 
                       name="search" 
                       value="{{ $search }}" 
                       placeholder="Ketik NIK atau nama..." 
                       class="w-full px-3 py-1.5 text-xs bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Status</label>
                <select name="status" class="w-full px-2.5 py-1.5 text-xs bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600">
                    <option value="">Semua Status</option>
                    <option value="submitted" {{ $status === 'submitted' ? 'selected' : '' }}>Submitted (Selesai)</option>
                    <option value="draft" {{ $status === 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Evaluator</label>
                <select name="evaluator_id" class="w-full px-2.5 py-1.5 text-xs bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600">
                    <option value="">Semua Evaluator</option>
                    @foreach ($evaluators as $ev)
                        <option value="{{ $ev->id }}" {{ $evaluatorId == $ev->id ? 'selected' : '' }}>{{ $ev->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="w-full px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-md shadow-xs transition-colors">
                    Filter
                </button>
                @if($search || $status || $evaluatorId || $divisi)
                    <a href="{{ route('admin.evaluations.index') }}" class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-medium rounded-md border border-slate-300 shrink-0">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Evaluations Table -->
    <div class="bg-white rounded-lg border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-4 border-b border-slate-200 flex items-center justify-between">
            <div class="text-xs text-slate-600">
                Menampilkan <strong>{{ $evaluations->firstItem() ?? 0 }} - {{ $evaluations->lastItem() ?? 0 }}</strong> dari total <strong>{{ number_format($evaluations->total()) }}</strong> berkas evaluasi
            </div>
            <a href="{{ route('admin.export.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-semibold rounded-md border border-slate-300 transition-colors">
                <svg class="w-4 h-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Export Hasil ke Excel
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-semibold text-slate-600 uppercase tracking-wider">
                        <th class="py-3 px-4">Tenaga Kerja</th>
                        <th class="py-3 px-4">Divisi & Jabatan</th>
                        <th class="py-3 px-4">Evaluator</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-center">Skor Rata-rata</th>
                        <th class="py-3 px-4">Tanggal Submit</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse ($evaluations as $eval)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-3.5 px-4">
                                <div class="font-semibold text-slate-900">{{ $eval->employee->nama ?? '-' }}</div>
                                <div class="text-xs font-mono text-slate-500">{{ $eval->employee->employee_code ?? '-' }}</div>
                            </td>
                            <td class="py-3.5 px-4 text-xs">
                                <div class="font-medium text-slate-800">{{ $eval->employee->jabatan ?? '-' }}</div>
                                <div class="text-slate-500">{{ $eval->employee->divisi ?? '-' }}</div>
                            </td>
                            <td class="py-3.5 px-4 text-xs text-slate-700">
                                {{ $eval->evaluator->name ?? '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($eval->isSubmitted())
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Submitted
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                        Draft
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($eval->total_score !== null)
                                    <span class="font-bold text-slate-900 text-sm bg-slate-100 px-2 py-0.5 rounded border border-slate-200">
                                        {{ number_format((float)$eval->total_score, 2) }}
                                    </span>
                                @else
                                    <span class="text-slate-400 text-xs">-</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-xs text-slate-600">
                                {{ $eval->submitted_at ? $eval->submitted_at->format('d M Y, H:i') : '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('admin.evaluations.show', $eval->id) }}" class="inline-flex items-center gap-1 text-xs font-semibold text-blue-700 hover:text-blue-900">
                                        Detail
                                    </a>
                                    @if($eval->isSubmitted())
                                        <span class="text-slate-300">|</span>
                                        <a href="{{ route('evaluations.print', $eval->id) }}" target="_blank" class="inline-flex items-center gap-1 text-xs font-semibold text-slate-600 hover:text-slate-900" title="Cetak Berkas">
                                            Cetak
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-10 text-center text-slate-500 text-sm">
                                Tidak ada data evaluasi yang sesuai filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($evaluations->hasPages())
            <div class="p-4 border-t border-slate-200">
                {{ $evaluations->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
