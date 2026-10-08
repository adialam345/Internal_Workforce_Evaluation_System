@extends('layouts.app')

@section('title', 'Kelola Evaluator')
@section('page_title', 'Kelola Akun Evaluator & Verifikator')
@section('page_subtitle', 'Manajemen ~70 akun evaluator, hak akses, status aktif, dan alokasi penilaian')

@section('content')
<div class="space-y-4">

    <!-- Search & Filters -->
    <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-xs flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <form method="GET" action="{{ route('admin.evaluators.index') }}" class="flex flex-wrap items-center gap-2 flex-1">
            <div class="w-full sm:w-64">
                <input type="text" 
                       name="search" 
                       value="{{ $search }}" 
                       placeholder="Cari nama, email, no HP..." 
                       class="w-full px-3 py-1.5 text-xs bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600">
            </div>

            <select name="department" class="px-2.5 py-1.5 text-xs bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600">
                <option value="">Semua Departemen</option>
                @foreach ($departments as $dept)
                    <option value="{{ $dept }}" {{ $department === $dept ? 'selected' : '' }}>{{ $dept }}</option>
                @endforeach
            </select>

            <select name="status" class="px-2.5 py-1.5 text-xs bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600">
                <option value="">Semua Status</option>
                <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
            </select>

            <button type="submit" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-md shadow-xs transition-colors">
                Filter
            </button>
            @if($search || $department || $status)
                <a href="{{ route('admin.evaluators.index') }}" class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-medium rounded-md border border-slate-300">
                    Reset
                </a>
            @endif
        </form>

        <a href="{{ route('admin.evaluators.create') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-md shadow-xs transition-colors shrink-0">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            Tambah Akun Evaluator
        </a>
    </div>

    <!-- Evaluators Table -->
    <div class="bg-white rounded-lg border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-semibold text-slate-600 uppercase tracking-wider">
                        <th class="py-3 px-4 sm:px-6">Nama Evaluator</th>
                        <th class="py-3 px-4">Departemen & Kontak</th>
                        <th class="py-3 px-4 text-center">Tenaga Kerja</th>
                        <th class="py-3 px-4 text-center">Selesai (Submitted)</th>
                        <th class="py-3 px-4 text-center">Draft</th>
                        <th class="py-3 px-4 text-center">Status Akun</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse ($evaluators as $ev)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-3.5 px-4 sm:px-6">
                                <div class="font-semibold text-slate-900">{{ $ev->name }}</div>
                                <div class="text-xs text-slate-500 font-mono">{{ $ev->email }}</div>
                            </td>
                            <td class="py-3.5 px-4 text-xs">
                                <div class="font-medium text-slate-800">{{ $ev->department ?? 'General' }}</div>
                                <div class="text-slate-500">{{ $ev->phone ?? '-' }}</div>
                            </td>
                            <td class="py-3.5 px-4 text-center font-bold text-slate-900">
                                {{ number_format($ev->total_assigned) }}
                            </td>
                            <td class="py-3.5 px-4 text-center font-bold text-emerald-700">
                                {{ number_format($ev->total_submitted) }}
                            </td>
                            <td class="py-3.5 px-4 text-center text-xs text-amber-700 font-semibold">
                                {{ number_format($ev->total_draft) }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($ev->is_active)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('admin.evaluators.show', $ev->id) }}" class="p-1 text-slate-600 hover:text-blue-700" title="Lihat Daftar Anggota">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a href="{{ route('admin.evaluators.edit', $ev->id) }}" class="p-1 text-slate-600 hover:text-blue-700" title="Edit Akun">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.evaluators.toggle', $ev->id) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin mengubah status aktif akun {{ $ev->name }}?');">
                                        @csrf
                                        <button type="submit" class="p-1 text-xs font-semibold {{ $ev->is_active ? 'text-amber-600 hover:text-amber-800' : 'text-emerald-600 hover:text-emerald-800' }}" title="{{ $ev->is_active ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}">
                                            @if($ev->is_active)
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                            @else
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            @endif
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-10 text-center text-slate-500 text-sm">
                                Tidak ada akun Evaluator yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($evaluators->hasPages())
            <div class="p-4 border-t border-slate-200">
                {{ $evaluators->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
