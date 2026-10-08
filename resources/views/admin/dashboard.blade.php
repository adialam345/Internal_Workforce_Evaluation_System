@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page_title', 'Ringkasan & Dashboard Progress')
@section('page_subtitle', 'Pemantauan evaluasi 4.000+ tenaga kerja dan progress 70+ akun evaluator')

@section('content')
<div class="space-y-6">

    <!-- KPI Metric Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1: Total Tenaga Kerja -->
        <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Tenaga Kerja</span>
                <span class="p-2 bg-blue-50 text-blue-700 rounded-md">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </span>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-2xl font-bold text-slate-900">{{ number_format($totalEmployees) }}</span>
                <span class="text-xs text-slate-500">Orang</span>
            </div>
            <div class="mt-2 text-xs text-slate-500 flex justify-between">
                <span>Ter-assign: <strong class="text-slate-700">{{ number_format($assignedEmployees) }}</strong></span>
                <span>Belum: <strong class="text-rose-600">{{ number_format($unassignedEmployees) }}</strong></span>
            </div>
        </div>

        <!-- Card 2: Sudah Dievaluasi (Submitted) -->
        <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Selesai Evaluasi</span>
                <span class="p-2 bg-emerald-50 text-emerald-700 rounded-md">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-2xl font-bold text-emerald-700">{{ number_format($submittedEvaluations) }}</span>
                <span class="text-xs text-slate-500">Berkas Submitted</span>
            </div>
            <div class="mt-2 text-xs text-slate-500">
                Draft tersimpan: <strong class="text-amber-600">{{ number_format($draftEvaluations) }}</strong> berkas
            </div>
        </div>

        <!-- Card 3: Belum Dievaluasi -->
        <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Belum Dievaluasi</span>
                <span class="p-2 bg-amber-50 text-amber-700 rounded-md">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-2xl font-bold text-slate-900">{{ number_format($notStartedEmployees) }}</span>
                <span class="text-xs text-slate-500">Tenaga Kerja</span>
            </div>
            <div class="mt-2 text-xs text-slate-500">
                Perlu ditindaklanjuti oleh Evaluator
            </div>
        </div>

        <!-- Card 4: Progress Keseluruhan -->
        <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Progress Total</span>
                <span class="p-2 bg-indigo-50 text-indigo-700 rounded-md">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </span>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-2xl font-bold text-blue-700">{{ $overallProgress }}%</span>
                <span class="text-xs text-slate-500">Tercapai</span>
            </div>
            <div class="mt-2 w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                <div class="bg-blue-600 h-2 rounded-full transition-all duration-500" style="width: {{ $overallProgress }}%"></div>
            </div>
        </div>
    </div>

    <!-- Quick Action Bar -->
    <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-xs flex flex-wrap items-center justify-between gap-3">
        <div class="text-xs text-slate-600 font-medium">
            Aksi Cepat Manajemen:
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.employees.create') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-md shadow-xs transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Tenaga Kerja
            </a>
            <a href="{{ route('admin.evaluators.create') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded-md shadow-xs transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                Tambah Akun Evaluator
            </a>
            <a href="{{ route('admin.import.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-semibold rounded-md transition-colors border border-slate-300">
                <svg class="w-4 h-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                Import Excel
            </a>
            <a href="{{ route('admin.export.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-semibold rounded-md transition-colors border border-slate-300">
                <svg class="w-4 h-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Export Excel
            </a>
        </div>
    </div>

    <!-- Evaluator Progress Monitoring Table -->
    <div class="bg-white rounded-lg border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-4 sm:px-6 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Monitoring Progress per Evaluator</h3>
                <p class="text-xs text-slate-500 mt-0.5">Dihitung otomatis: Selesai / Ditugaskan * 100%</p>
            </div>
            <div class="text-xs text-slate-600">
                Total Evaluator: <strong>{{ $totalEvaluators }}</strong> (Aktif: {{ $activeEvaluators }}, Nonaktif: {{ $inactiveEvaluators }})
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-semibold text-slate-600 uppercase tracking-wider">
                        <th class="py-3 px-4 sm:px-6">Nama Evaluator</th>
                        <th class="py-3 px-4">Departemen / Divisi</th>
                        <th class="py-3 px-4 text-center">Ditugaskan</th>
                        <th class="py-3 px-4 text-center">Selesai</th>
                        <th class="py-3 px-4 text-center">Sisa</th>
                        <th class="py-3 px-4 text-center">Draft</th>
                        <th class="py-3 px-4">Progress (%)</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse ($evaluators as $ev)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-3.5 px-4 sm:px-6">
                                <div class="font-semibold text-slate-900">{{ $ev->name }}</div>
                                <div class="text-xs text-slate-500">{{ $ev->email }}</div>
                            </td>
                            <td class="py-3.5 px-4 text-xs text-slate-700">
                                {{ $ev->department ?? '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-center font-semibold text-slate-900">
                                {{ number_format($ev->total_assigned) }}
                            </td>
                            <td class="py-3.5 px-4 text-center font-semibold text-emerald-700">
                                {{ number_format($ev->total_submitted) }}
                            </td>
                            <td class="py-3.5 px-4 text-center font-semibold {{ $ev->remaining > 0 ? 'text-amber-700' : 'text-slate-400' }}">
                                {{ number_format($ev->remaining) }}
                            </td>
                            <td class="py-3.5 px-4 text-center text-xs text-slate-600">
                                {{ number_format($ev->total_draft) }}
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-24 bg-slate-100 rounded-full h-2 overflow-hidden">
                                        <div class="h-2 rounded-full {{ $ev->progress == 100 ? 'bg-emerald-600' : 'bg-blue-600' }}" style="width: {{ $ev->progress }}%"></div>
                                    </div>
                                    <span class="text-xs font-bold text-slate-800">{{ $ev->progress }}%</span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <a href="{{ route('admin.evaluators.show', $ev->id) }}" class="inline-flex items-center gap-1 text-xs font-semibold text-blue-700 hover:text-blue-900">
                                    Lihat Anggota
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-slate-500 text-sm">
                                Belum ada akun Evaluator yang terdaftar di sistem.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
