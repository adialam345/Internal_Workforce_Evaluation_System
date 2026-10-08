@extends('layouts.app')

@section('title', 'Data Tenaga Kerja')
@section('page_title', 'Kelola Data Tenaga Kerja')
@section('page_subtitle', 'Manajemen data 4.000+ tenaga kerja, penugasan evaluator, dan status evaluasi')

@section('content')
<div class="space-y-4" x-data="{ 
    selected: [],
    selectAll: false,
    toggleAll() {
        if (this.selectAll) {
            this.selected = Array.from(document.querySelectorAll('.emp-checkbox')).map(el => el.value);
        } else {
            this.selected = [];
        }
    }
}">

    <!-- Filter & Search Toolbar -->
    <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-xs">
        <form method="GET" action="{{ route('admin.employees.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <!-- Search -->
            <div class="lg:col-span-2">
                <label class="block text-xs font-semibold text-slate-700 mb-1">Cari NIK / Nama / Jabatan</label>
                <div class="relative">
                    <input type="text" 
                           name="search" 
                           value="{{ $search }}" 
                           placeholder="Ketik NIK atau nama..." 
                           class="w-full pl-9 pr-3 py-1.5 text-xs bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600">
                    <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            <!-- Filter Divisi -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Divisi</label>
                <select name="divisi" class="w-full px-2.5 py-1.5 text-xs bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600">
                    <option value="">Semua Divisi</option>
                    @foreach ($divisions as $d)
                        <option value="{{ $d }}" {{ $divisi === $d ? 'selected' : '' }}>{{ $d }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Evaluator -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Evaluator</label>
                <select name="evaluator_id" class="w-full px-2.5 py-1.5 text-xs bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600">
                    <option value="">Semua Evaluator</option>
                    <option value="unassigned" {{ $evaluatorId === 'unassigned' ? 'selected' : '' }}>Belum Ditugaskan</option>
                    @foreach ($evaluators as $ev)
                        <option value="{{ $ev->id }}" {{ $evaluatorId == $ev->id ? 'selected' : '' }}>{{ $ev->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Status Evaluasi & Action -->
            <div class="flex items-end gap-2">
                <div class="flex-1">
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Status Evaluasi</label>
                    <select name="evaluation_status" class="w-full px-2.5 py-1.5 text-xs bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600">
                        <option value="">Semua Status</option>
                        <option value="submitted" {{ $evaluationStatus === 'submitted' ? 'selected' : '' }}>Selesai (Submitted)</option>
                        <option value="draft" {{ $evaluationStatus === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="none" {{ $evaluationStatus === 'none' ? 'selected' : '' }}>Belum Dievaluasi</option>
                    </select>
                </div>
                <button type="submit" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-md shadow-xs transition-colors shrink-0">
                    Filter
                </button>
                @if($search || $divisi || $evaluatorId || $status || $evaluationStatus)
                    <a href="{{ route('admin.employees.index') }}" class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-medium rounded-md border border-slate-300 shrink-0" title="Reset filter">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Bulk Assignment Form Trigger (Visible when items selected) -->
    <div x-show="selected.length > 0" 
         x-transition
         class="bg-blue-50 border border-blue-200 p-3 rounded-lg flex flex-wrap items-center justify-between gap-3 text-xs" 
         style="display: none;">
        <div class="font-semibold text-blue-900 flex items-center gap-2">
            <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <span x-text="selected.length"></span> tenaga kerja dipilih untuk penugasan massal:
        </div>

        <form method="POST" action="{{ route('admin.employees.bulk_assign') }}" class="flex items-center gap-2">
            @csrf
            <template x-for="id in selected" :key="id">
                <input type="hidden" name="employee_ids[]" :value="id">
            </template>

            <select name="target_evaluator_id" required class="px-2.5 py-1 text-xs bg-white border border-slate-300 rounded-md focus:ring-2 focus:ring-blue-600 focus:outline-none">
                <option value="">-- Pilih Evaluator Tujuan --</option>
                <option value="">Kosongkan (Unassign)</option>
                @foreach ($evaluators as $ev)
                    <option value="{{ $ev->id }}">{{ $ev->name }} ({{ $ev->department ?? 'General' }})</option>
                @endforeach
            </select>

            <button type="submit" onclick="return confirm('Apakah Anda yakin ingin memperbarui penugasan evaluator untuk tenaga kerja yang dipilih?')" class="px-3 py-1 bg-blue-700 hover:bg-blue-800 text-white font-semibold rounded-md shadow-xs">
                Tugaskan Massal
            </button>
        </form>
    </div>

    <!-- Main Table Container -->
    <div class="bg-white rounded-lg border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-4 border-b border-slate-200 flex items-center justify-between">
            <div class="text-xs text-slate-600">
                Menampilkan <strong>{{ $employees->firstItem() ?? 0 }} - {{ $employees->lastItem() ?? 0 }}</strong> dari total <strong>{{ number_format($employees->total()) }}</strong> data tenaga kerja
            </div>
            <a href="{{ route('admin.employees.create') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-md shadow-xs transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Tenaga Kerja
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-semibold text-slate-600 uppercase tracking-wider">
                        <th class="py-3 px-3 sm:px-4 w-10 text-center">
                            <input type="checkbox" x-model="selectAll" @change="toggleAll()" class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        </th>
                        <th class="py-3 px-3">NIK</th>
                        <th class="py-3 px-4">Nama Lengkap</th>
                        <th class="py-3 px-4">Jabatan & Divisi</th>
                        <th class="py-3 px-4">Evaluator</th>
                        <th class="py-3 px-4 text-center">Status Evaluasi</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse ($employees as $emp)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-3 px-3 sm:px-4 text-center">
                                <input type="checkbox" 
                                       value="{{ $emp->id }}" 
                                       x-model="selected" 
                                       class="emp-checkbox w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            </td>
                            <td class="py-3 px-3 font-mono text-xs font-bold text-slate-800">
                                {{ $emp->employee_code }}
                            </td>
                            <td class="py-3 px-4">
                                <div class="font-semibold text-slate-900">{{ $emp->nama }}</div>
                                <div class="text-[11px] text-slate-500">{{ $emp->unit ?? '-' }}</div>
                            </td>
                            <td class="py-3 px-4 text-xs">
                                <div class="font-medium text-slate-800">{{ $emp->jabatan ?? '-' }}</div>
                                <div class="text-slate-500">{{ $emp->divisi ?? '-' }}</div>
                            </td>
                            <td class="py-3 px-4 text-xs">
                                @if($emp->evaluator)
                                    <span class="inline-flex items-center gap-1 font-medium text-slate-800">
                                        <svg class="w-3.5 h-3.5 text-blue-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        {{ $emp->evaluator->name }}
                                    </span>
                                @else
                                    <span class="text-rose-600 font-medium">Belum Ditugaskan</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($emp->evaluation)
                                    @if($emp->evaluation->isSubmitted())
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            Selesai (Skor: {{ number_format((float)$emp->evaluation->total_score, 1) }})
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
                            <td class="py-3 px-4 text-right">
                                <div class="inline-flex items-center gap-2">
                                    @if($emp->evaluation && $emp->evaluation->isSubmitted())
                                        <a href="{{ route('evaluations.print', $emp->evaluation->id) }}" target="_blank" title="Cetak Hasil Evaluasi" class="p-1 text-slate-500 hover:text-blue-700">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                        </a>
                                    @endif
                                    <a href="{{ route('admin.employees.edit', $emp->id) }}" class="p-1 text-slate-600 hover:text-blue-600" title="Edit Data">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.employees.destroy', $emp->id) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tenaga kerja {{ $emp->nama }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 text-slate-400 hover:text-rose-600" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-10 text-center text-slate-500 text-sm">
                                <p class="font-semibold text-slate-700">Tidak ada data tenaga kerja yang sesuai filter.</p>
                                <p class="text-xs text-slate-400 mt-1">Coba sesuaikan kata kunci pencarian atau reset filter.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Server-side Pagination -->
        @if ($employees->hasPages())
            <div class="p-4 border-t border-slate-200">
                {{ $employees->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
